<?php

namespace App\Http\Controllers;

use App\Events\CreatePelatihan;
use App\Events\ProcessPelatihan;
use App\Http\Requests\StorePelatihanRequest;
use App\Models\Jabatan;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Status;
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
        ];
        $data['list'] = Pelatihan::select('judul', 'tgl_mulai', 'tgl_selesai', 'kuota', 'is_publish', 'slug', 'status_id', 'created_by')->with(['status'])->when($data['filter']['search'], function ($query) use ($data) {
            $query->where('judul', 'like', '%' . $data['filter']['search'] . '%');
        })->when($data['filter']['status'], function ($query) use ($data) {
            $query->whereHas('status', function ($query2) use ($data) {
                $query2->where('slug', $data['filter']['status']);
            });
        })->orderByDesc('id')->paginate($data['filter']['limit'])->appends($data['filter']);
        $data['status'] = Status::orderBy('id', 'ASC')->get(['slug', 'name']);
        return Inertia::render('Pelatihan/Index', $data);
    }

    public function create()
    {
        $data['title'] = "Tambah Pelatihan";
        $data['jabatans'] = Jabatan::where('kd_jabatan', '!=', "R")->orderBy('kd_parent', 'ASC')->orderByDesc('kd_jabatan')->get(['kd_parent', 'kd_jabatan', 'jabatan']);
        return Inertia::render('Pelatihan/Create', $data);
    }

    public function store(StorePelatihanRequest $request)
    {
        $data = $request->safe()->except(['file_bahan', 'kd_jabatan']);
        $slug = Str::slug($data['judul']);
        $data['slug'] = Pelatihan::where('slug', $slug)->count() > 0 ? $slug . '-' . Str::random(1) : $slug;
        $data['ket_jabatan'] = json_encode($data['ket_jabatan']);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $pelatihan = Pelatihan::create($data);
        event(new CreatePelatihan($pelatihan, $request->post('kd_jabatan'), $request->file('file_bahan')));
        $request->session()->flash('flash.msg', $pelatihan->status->flash);
        $request->session()->flash('flash.error', false);
        return redirect()->route('pelatihan.index');
    }

    public function show(Pelatihan $pelatihan, Request $request)
    {
        $data['title'] = "Detail Pelatihan";
        $data['pelatihan'] = $pelatihan->load(['jabatan.jabatan',  'bahan',  'bobot.jabatan', 'status']);
        $data['jabatan'] = Jabatan::select('kd_jabatan')->whereIn('kd_jabatan', $pelatihan->jabatan()->pluck('kd_jabatan'))->withCount('peserta')->get();
        $data['riwayat'] = [];
        foreach ($data['jabatan'] as $key) {
            $data['riwayat'][$key->kd_jabatan] = $key->peserta_count;
        }
        $data['riwayat']['ikut_pelatihan'] = in_array($pelatihan->status_id, [1, 2, 3, 5]) ? 0 : Peserta::whereIn('kd_jabatan', $pelatihan->bobot->pluck('key')->all())->whereHas('riwayatPelatihan', function ($query) use ($pelatihan) {
            $query->where('jenis_pelatihan', $pelatihan->jenis_pelatihan);
        })->count();

        $data['riwayat']['tidak_pelatihan'] = in_array($pelatihan->status_id, [1, 2, 3, 5]) ? 0 : Peserta::whereIn('kd_jabatan', $pelatihan->bobot->pluck('key')->all())->whereDoesntHave('riwayatPelatihan', function ($query) use ($pelatihan) {
            $query->where('jenis_pelatihan', $pelatihan->jenis_pelatihan);
        })->count();

        $search = $request->get('search');
        $data['filter'] = [
            'limit' => $request->get('limit') ?? 10,
            'search' => $search,
            'new' => $request->get('new') ?? "",
            'confirmed' => $request->get('confirmed') ?? ""
        ];
        $data['peserta'] = $pelatihan->status_id == 4 || $pelatihan->status_id > 5 ? Peserta::select('nip', 'nama_lengkap', 'kd_jabatan')->whereIn('kd_jabatan', $pelatihan->bobot->pluck('key')->all())->when($search, function ($query) use ($search) {
            $query->where('nama_lengkap', 'like', '%' . $search . '%')->orWhere('nip', 'like', '%' . $search . '%')->orWhereHas('jabatan', function ($query2) use ($search) {
                $query2->where('jabatan', 'like', '%' . $search . '%');
            });
        })->when($data['filter']['new'] == "yes", function ($query) {
            $query->whereHas('riwayatPelatihan', function ($query) {
                $query->where('jenis_pelatihan', 'fungsional');
            });
        })->when($data['filter']['new'] == "no", function ($query) {
            $query->whereDoesntHave('riwayatPelatihan', function ($query) {
                $query->where('jenis_pelatihan', 'fungsional');
            });
        })->when($data['filter']['confirmed'] == "approved", function ($query) {
            $query->whereHas('pendaftaran', function ($query) {
                $query->where('confirmed_at', "!=", NULL);
                $query->where('rejected_at', NULL);
            });
        })->when($data['filter']['confirmed'] == "approved", function ($query) {
            $query->whereHas('pendaftaran', function ($query) {
                $query->where('confirmed_at', "!=", NULL);
                $query->where('approved_at', "!=", NULL);
                $query->where('rejected_at', NULL);
            });
        })->when($data['filter']['confirmed'] == "confirmed", function ($query) {
            $query->whereHas('pendaftaran', function ($query) {
                $query->where('confirmed_at', "!=", NULL);
                $query->where('rejected_at', NULL);
            });
        })->when($data['filter']['confirmed'] == "waiting", function ($query) {
            $query->whereHas('pendaftaran', function ($query) {
                $query->where('confirmed_at', NULL);
                $query->where('rejected_at', NULL);
            });
        })->withCount(['riwayatPelatihan' => function (Builder $query) use ($pelatihan) {
            $query->where('jenis_pelatihan', $pelatihan->jenis_pelatihan);
        }])->with('pendaftaran', function ($query) use ($pelatihan) {
            $query->where('pelatihan_id', $pelatihan->id);
        })->with('jabatan')->orderBy('nama_lengkap', 'ASC')->paginate($data['filter']['limit'])->appends($data['filter']) : [];

        $data['confirmed']['approved'] = $pelatihan->status_id > 7 ? Pendaftaran::where('approved_at', '!=', null)->count() : 0;
        $data['confirmed']['confirmed'] = $pelatihan->status_id > 7 ? Pendaftaran::where('confirmed_at', '!=', null)->where('approved_at', null)->count() : 0;
        $data['confirmed']['rejected'] = $pelatihan->status_id > 7 ? Pendaftaran::where('confirmed_at',  null)->where('rejected_at', '!=', null)->count() : 0;
        $data['confirmed']['waiting'] = $pelatihan->status_id > 7 ? Pendaftaran::where('confirmed_at', null)->where('rejected_at', null)->orWhere('confirmed_at', '!=', null)->where('rejected_at', '!=', null)->count() : 0;
        return Inertia::render('Pelatihan/Show', $data);
    }

    public function edit(Pelatihan $pelatihan)
    {
        $data['title'] = "Ubah Pelatihan";
        $data['jabatans'] = Jabatan::where('kd_jabatan', '!=', "R")->orderBy('kd_parent', 'ASC')->orderByDesc('kd_jabatan')->get(['kd_parent', 'kd_jabatan', 'jabatan']);
        $data['pelatihan'] = $pelatihan->load(['jabatan',  'bahan']);
        return Inertia::render('Pelatihan/Edit', $data);
    }

    public function update(StorePelatihanRequest $request, Pelatihan $pelatihan)
    {
        $data = $request->safe()->except(['file_bahan', 'kd_jabatan']);
        $slug = Str::slug($data['judul']);
        $data['slug'] = Pelatihan::where('slug', $slug)->count() > 0 ? $slug . '-' . time() : $slug;
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $pelatihan->update($data);
        event(new CreatePelatihan($pelatihan, $request->post('kd_jabatan'), $request->file('file_bahan')));
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
            'batas_konfirmasi' => 'date',
            'mulai_pendaftaran' => 'date',
            'selesai_pendaftaran' => 'date',
        ]);
        $this->authorize('process', [$pelatihan, $data['status_id']]);
        $status = Status::find($data['status_id']);
        event(new ProcessPelatihan($pelatihan, $data));
        $request->session()->flash('flash.msg', $status->flash);
        $request->session()->flash('flash.error', false);
        return back();
    }
}
