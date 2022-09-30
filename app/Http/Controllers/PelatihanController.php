<?php

namespace App\Http\Controllers;

use App\Events\CreatePelatihan;
use App\Events\ProcessPelatihan;
use App\Http\Requests\StorePelatihanRequest;
use App\Models\Jabatan;
use App\Models\Katalog;
use App\Models\Pelatihan;
use App\Models\PelatihanBobot;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Status;
use App\Models\Variable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PelatihanController extends Controller
{
    function __construct()
    {
        $this->authorizeResource(Pelatihan::class);
    }
    public function index(Request $request)
    {
        $data['title'] = "Pelatihan";
        $data['filter'] = [
            'limit' => $request->get('limit') ?? 10,
            'search' => $request->get('search'),
            'status' => $request->get('status') ?? "",
            'year' => $request->get('year') ?? "",
            'month' => $request->get('month') ?? "",
        ];

        $data['list'] = Pelatihan::select('mulai_pendaftaran', 'selesai_pendaftaran', 'mulai_pelatihan', 'selesai_pelatihan',  'kuota',  'slug', 'status_id', 'katalog_id', 'created_by')->whereHas('katalog', function ($query) use ($data) {
            $query->when($data['filter']['search'], function ($query2) use ($data) {
                $query2->where('judul', 'like', '%' . $data['filter']['search'] . '%');
            });
        })->when($data['filter']['status'], function ($query) use ($data) {
            $query->whereHas('status', function ($query2) use ($data) {
                $query2->where('slug', $data['filter']['status']);
            });
        })->when($data['filter']['year'], function ($query) use ($data) {
            $query->whereYear('mulai_pelatihan', $data['filter']['year']);
        })->when($data['filter']['month'], function ($query) use ($data) {
            $query->whereMonth('mulai_pelatihan', $data['filter']['month']);
        })->with(['status', 'katalog'])->orderByDesc('id')->paginate($data['filter']['limit'])->appends($data['filter']);
        $data['years'] = range(date('Y'), 2020);
        $data['months'] = range(1, 12);
        $data['status'] = Status::orderBy('id', 'ASC')->get(['slug', 'name']);
        return Inertia::render('Pelatihan/Index', $data);
    }

    public function create()
    {
        $data['title'] = "Tambah Pelatihan";
        $data['katalog'] = Katalog::where(['is_publish' => true, 'deleted_at' => null])->orderByDesc('id')->get(['id', 'judul']);
        return Inertia::render('Pelatihan/Create', $data);
    }

    public function store(StorePelatihanRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = time();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $pelatihan = Pelatihan::create($data);
        event(new CreatePelatihan($pelatihan));
        $request->session()->flash('flash.msg', $pelatihan->status->flash);
        $request->session()->flash('flash.error', false);
        return redirect()->route('pelatihan.index');
    }


    public function show(Pelatihan $pelatihan, Request $request)
    {
        $data['title'] = "Detail Pelatihan";
        $data['pelatihan'] = $pelatihan->load(['status:id,next_id,prev_id,name,role_id,notification', 'pendaftaran']);
        $data['katalog'] = $katalog = Katalog::where('id', $pelatihan->katalog_id)->with(['jabatan' => function ($query) {
            $query->select('silat_katalog_jabatan.kd_jabatan', 'silat_katalog_jabatan.katalog_id')->join('tbk_jabatan', 'tbk_jabatan.kd_jabatan', '=', 'silat_katalog_jabatan.kd_jabatan')->orderBy('tbk_jabatan.kd_group', 'asc')->orderBy('silat_katalog_jabatan.kd_jabatan', 'desc')->with('jabatan:kd_jabatan,jabatan');
        },  'bahan:katalog_id,file,name',])->first();

        $kd_jabatan = $katalog->jabatan()->pluck('kd_jabatan');
        $data['kriteria'] = Variable::where('group', 'bobot')->when($katalog->jenis_pelatihan != 'fungsional', function ($query) {
            $query->whereNotIn('key', ['less_kredit', 'more_kredit']);
        })->when($pelatihan->instansi != 'pusat_uml', function ($query) {
            $query->whereNotIn('key',  ['instansi_uml', 'instansi_pusat']);
        })->orWhere('group', 'jabatan')->whereIn('key', $kd_jabatan)->orderBy('order', 'asc')->get(['key', 'value']);

        $ket_jabatan = json_decode($katalog->ket_jabatan);
        $data['bobots'] = PelatihanBobot::select('id', 'key', 'bobot')->with(['variable:key,value'])->where('pelatihan_id', $pelatihan->id)->orderByDesc('id')->get();
        $peserta = Peserta::whereHas('pendaftaran', function ($query) use ($pelatihan) {
            $query->where('pelatihan_id', $pelatihan->id);
        })->get();
        $jumlah_peserta = [];
        $jenis_pelatihan = $katalog->jenis_pelatihan;
        foreach ($data['bobots'] as $key) {
            if ($key->key == 'less_kredit') {
                $jumlah_peserta[$key->key] = $peserta->where('angkat_kredit', '<', $katalog->angka_kredit)->count();
            } elseif ($key->key == 'more_kredit') {
                $jumlah_peserta[$key->key] = $peserta->where('angkat_kredit', '>=', $katalog->angka_kredit)->count();
            } elseif ($key->key == 'instansi_uml') {
                $jumlah_peserta[$key->key] = $peserta->where('lokasi_dinas', 'uml')->count();
            } elseif ($key->key == 'instansi_pusat') {
                $jumlah_peserta[$key->key] = $peserta->where('lokasi_dinas', 'pusat')->count();
            } elseif ($key->key == 'pernah_gagal') {
                $jumlah_peserta[$key->key] = Peserta::whereIn('kd_jabatan', $kd_jabatan)->when($katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
                    $query->whereIn('keterangan_jbt', $ket_jabatan);
                })->whereHas('riwayatPelatihan', function ($query) use ($jenis_pelatihan) {
                    $query->where('jenis_pelatihan', $jenis_pelatihan)->where('status', '!=', 'Disetujui');
                })->count();
            } elseif ($key->key == 'peserta_mengulang') {
                $jumlah_peserta[$key->key] = Peserta::whereIn('kd_jabatan', $kd_jabatan)->when($katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
                    $query->whereIn('keterangan_jbt', $ket_jabatan);
                })->whereHas('riwayatPelatihan', function ($query) use ($jenis_pelatihan) {
                    $query->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
                })->count();
            } else {
                $jumlah_peserta[$key->key] = $peserta->where('kd_jabatan', $key->key)->count();
            }
        }
        $data['jumlah_peserta'] = $jumlah_peserta;

        $data['filter'] = [
            'limit' => $request->get('limit') ?? 10,
            'search' => $request->get('search'),
            'key' => $request->get('key') ?? "nip",
            'new' => $request->get('new') ?? "",
            'confirmed' => $request->get('confirmed') ?? ""
        ];
        $filter = $data['filter'];
        $data['peserta'] = Peserta::select('tbm_pegawai.nip', 'nama_lengkap', 'kd_jabatan', 'jumlah_bobot', 'confirmed_at', 'approved_at')->whereHas('pendaftaran', function ($query) use ($pelatihan, $filter) {
            $query->where('pelatihan_id', $pelatihan->id)
                ->when($filter['confirmed'] == "approved", function ($query2) {
                    $query2->whereNotNull('approved_at');
                })->when($filter['confirmed'] == "rejected", function ($query2) use ($pelatihan) {
                    $query2->whereNotNull('rejected_at');
                })->when($filter['confirmed'] == "confirmed", function ($query2) use ($pelatihan) {
                    $query2->whereNotNull('confirmed_at')->whereNull('approved_at');
                })->when($filter['confirmed'] == "registered", function ($query2) use ($pelatihan) {
                    $query2->whereNotNull('registered_at')->whereNull('confirmed_at');
                })->when($filter['confirmed'] == "unregistered", function ($query2) use ($pelatihan) {
                    $query2->whereNull('registered_at');
                });
        })->when($filter['search'], function ($query) use ($filter) {
            if ($filter['key'] == 'jabatan') {
                $query->whereHas('jabatan', function ($query2) use ($filter) {
                    $query2->where($filter['key'], 'like', '%' .  $filter['search'] . '%');
                });
            } else {
                $query->where($filter['key'] == 'nip' ? 'tbm_pegawai.nip' : $filter['key'], 'like', '%' .  $filter['search'] . '%');
            }
        })->with('pendaftaran', function ($query) use ($pelatihan) {
            $query->where('pelatihan_id', $pelatihan->id);
        })->join('silat_pendaftaran', function ($query) use ($pelatihan) {
            $query->on('silat_pendaftaran.nip', '=', 'tbm_pegawai.nip')->where('silat_pendaftaran.pelatihan_id', $pelatihan->id);
        })->with('jabatan')->orderBy('approved_at', 'asc')->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'ASC')->orderBy('nama_lengkap', 'ASC')->paginate($data['filter']['limit'])->appends($data['filter']);

        $pendaftaran = Pendaftaran::where('pelatihan_id', $pelatihan->id)->get();
        $data['confirmed']['approved'] =  $pendaftaran->where('approved_at', '!=', null)->count();
        $data['confirmed']['confirmed'] =  $pendaftaran->where('confirmed_at', '!=', null)->where('approved_at', null)->count();
        $data['confirmed']['rejected'] =  $pendaftaran->where('rejected_at', '!=', null)->count();
        $data['confirmed']['waiting'] =  $pendaftaran->where('confirmed_at', null)->where('rejected_at', null)->count();
        return Inertia::render('Pelatihan/Show', $data);
    }

    public function edit(Pelatihan $pelatihan)
    {
        $data['title'] = "Tambah Pelatihan";
        $data['katalog'] = Katalog::where(['is_publish' => true, 'deleted_at' => null])->orderByDesc('id')->get(['id', 'judul']);
        $data['pelatihan'] = $pelatihan;
        return Inertia::render('Pelatihan/Edit', $data);
    }

    public function update(StorePelatihanRequest $request, Pelatihan $pelatihan)
    {
        $data = $request->validated();
        $data['updated_by'] = Auth::id();
        $pelatihan->update($data);
        event(new CreatePelatihan($pelatihan));
        $request->session()->flash('flash.msg', $pelatihan->status->flash);
        $request->session()->flash('flash.error', false);
        return redirect()->route('pelatihan.index');
    }

    public function destroy(Request $request, Pelatihan $pelatihan)
    {
        $pelatihan->jabatan()->delete();
        $pelatihan->bahan()->delete();
        $pelatihan->logs()->delete();
        $pelatihan->delete();
        $request->session()->flash('flash.msg', 'Data berhasil dihapus');
        $request->session()->flash('flash.error', false);
        return back();
    }

    public function process(Request $request, Pelatihan $pelatihan)
    {
        $data = $request->validate([
            'status_id' => "required",
            'keterangan' => 'max:255',
            'mulai_pendaftaran' => 'date',
            'selesai_pendaftaran' => 'date',
            'batas_konfirmasi' => 'date',
        ]);
        $this->authorize('process', [$pelatihan, $data['status_id']]);
        $status = Status::find($data['status_id']);
        event(new ProcessPelatihan($pelatihan, $data));
        $request->session()->flash('flash.msg', $status->flash);
        $request->session()->flash('flash.error', false);
        return back();
    }
}
