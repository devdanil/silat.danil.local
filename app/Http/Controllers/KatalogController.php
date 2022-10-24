<?php

namespace App\Http\Controllers;

use App\Events\KatalogEvent;
use App\Http\Requests\KatalogRequest;
use App\Models\Jabatan;
use App\Models\Katalog;
use App\Models\Peserta;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class KatalogController extends Controller
{
  function __construct()
  {
    $this->authorizeResource(Katalog::class);
  }
  public function index(Request $request)
  {
    $data['title'] = "Katalog";
    $data['filter'] = [
      'limit' => $request->get('limit') ?? 10,
      'search' => $request->get('search'),
      'publish' => $request->get('publish') ?? "",
      'pelatihan' => $request->get('pelatihan') ?? "",
    ];
    $data['list'] = Katalog::select('judul', 'slug', 'jenis_pelatihan', 'is_publish')->whereNull('deleted_at')->when($data['filter']['search'], function ($query) use ($data) {
      $query->where('judul', 'like', '%' . $data['filter']['search'] . '%')->where('judul', 'like', '%' . $data['filter']['search'] . '%');
    })->when($data['filter']['publish'], function ($query) use ($data) {
      $query->where('is_publish', $data['filter']['publish'] == 'yes');
    })->when($data['filter']['pelatihan'], function ($query) use ($data) {
      $query->where('jenis_pelatihan', $data['filter']['pelatihan']);
    })->orderByDesc('id')->paginate($data['filter']['limit'])->appends($data['filter']);
    return Inertia::render('Katalog/Index', $data);
  }

  public function create()
  {
    $data['title'] = "Tambah Katalog";
    $data['jabatans'] = Jabatan::where('kd_jabatan', '!=', "R")->orderBy('kd_parent', 'ASC')->orderByDesc('kd_jabatan')->get(['kd_parent', 'kd_jabatan', 'jabatan']);
    $data['katalogs'] = Katalog::where(['is_publish' => true, 'deleted_at' => null])->orderByDesc('id')->get(['id', 'judul']);
    return Inertia::render('Katalog/Create', $data);
  }

  public function store(KatalogRequest $request)
  {
    $data = $request->safe()->except(['file_bahan', 'kd_jabatan']);
    $slug = Str::slug($data['judul']);
    $data['slug'] = Katalog::where('slug', $slug)->count() > 0 ? $slug . '-' . time() : $slug;
    $data['img']->store('public/img/katalog/');
    $data['img'] = asset('storage/img/katalog/' . $data['img']->hashName());
    $data['created_by'] = Auth::id();
    $data['updated_by'] = Auth::id();
    $data['syarat_katalog'] = $request->post('syarat_katalog') ? json_encode($request->post('syarat_katalog')) : null;
    $data['ket_jabatan'] = $request->post('ket_jabatan') ? json_encode($request->post('ket_jabatan')) : null;
    $katalog = Katalog::create($data);
    event(new KatalogEvent($katalog, $request->post('kd_jabatan'), $request->file('file_bahan')));
    $request->session()->flash('flash.msg', "Katalog berhasil disimpan");
    $request->session()->flash('flash.error', false);
    return redirect()->route('katalog.index');
  }

  public function show(Katalog $katalog)
  {
    $data['title'] = "Detail Katalog";
    $data['katalog'] = $katalog->load(['jabatan' => function ($query) {
      $query->select('silat_katalog_jabatan.kd_jabatan', 'silat_katalog_jabatan.katalog_id')->join('tbk_jabatan', 'tbk_jabatan.kd_jabatan', '=', 'silat_katalog_jabatan.kd_jabatan')->orderBy('tbk_jabatan.kd_group', 'asc')->orderBy('silat_katalog_jabatan.kd_jabatan', 'desc')->with('jabatan:kd_jabatan,jabatan');
    },  'bahan:katalog_id,file,name',]);
    $data['katalogs'] = $katalog->syarat_katalog && is_array(json_decode($katalog->syarat_katalog)) && count(json_decode($katalog->syarat_katalog)) > 0 ? Katalog::whereIn('id', json_decode($katalog->syarat_katalog))->get(['id', 'judul']) : [];
    return Inertia::render('Katalog/Show', $data);
  }

  public function edit(Katalog $katalog)
  {
    $data['title'] = "Ubah Katalog";
    $data['jabatans'] = Jabatan::where('kd_jabatan', '!=', "R")->orderBy('kd_parent', 'ASC')->orderByDesc('kd_jabatan')->get(['kd_jabatan', 'jabatan']);
    $data['katalogs'] = Katalog::where(['is_publish' => true, 'deleted_at' => null])->where('id', '!=', $katalog->id)->orderByDesc('id')->get(['id', 'judul']);
    $data['katalog'] = $katalog->load(['jabatan',  'bahan:katalog_id,name,file']);
    return Inertia::render('Katalog/Edit', $data);
  }

  public function update(KatalogRequest $request, Katalog $katalog)
  {
    $data = $request->safe()->except(['file_bahan', 'kd_jabatan', 'jenis_pelatihan', 'judul', 'img']);
    $img = $request->file('img');
    if ($img) {
      $img->store('public/img/katalog/');
      $data['img'] = asset('storage/img/katalog/' . $img->hashName());
    }

    $data['updated_by'] = Auth::id();
    $data['syarat_katalog'] = $request->post('syarat_katalog') ? json_encode($request->post('syarat_katalog')) : null;
    $data['ket_jabatan'] = $request->post('ket_jabatan') ? json_encode($request->post('ket_jabatan')) : null;
    $katalog->update($data);
    event(new KatalogEvent($katalog, $request->post('kd_jabatan'), $request->file('file_bahan')));
    $request->session()->flash('flash.msg', "Katalog berhasil diubah");
    $request->session()->flash('flash.error', false);
    return redirect()->route('katalog.index');
  }

  public function destroy(Request $request, Katalog $katalog)
  {
    $katalog->update(['deleted_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
    $request->session()->flash('flash.msg', 'Data berhasil dihapus');
    $request->session()->flash('flash.error', false);
    return back();
  }

  public function process(Request $request, Katalog $katalog)
  {
    $data = $request->validate([
      'status_id' => "required",
      'keterangan' => 'max:255',
    ]);
    $this->authorize('process', [$katalog, $data['status_id']]);
    $status = Status::find($data['status_id']);
    $data['updated_by'] = Auth::id();
    $katalog->update($data);
    $request->session()->flash('flash.msg', "data");
    $request->session()->flash('flash.error', false);
    return back();
  }
}
