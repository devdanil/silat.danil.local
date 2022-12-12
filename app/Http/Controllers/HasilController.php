<?php

namespace App\Http\Controllers;

use App\Exports\TemplateHasilExport;
use App\Imports\PelatihanImport;
use App\Models\Hasil;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class HasilController extends Controller
{
    function __construct()
    {
        $this->authorizeResource(Hasil::class);
    }
    public function index(Request $request)
    {
        $data['title'] = "Hasil Pelatihan";
        $data['filter'] = [
            'limit' => $request->get('limit') ?? 10,
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'predikat' => $request->get('predikat'),
        ];
        $data['list'] = Hasil::when($data['filter']['search'], function ($query) use ($data) {
            $query->whereHas('peserta', function ($query2) use ($data) {
                $query2->where('nama', 'like', '%' . $data['filter']['search'] . '%')
                    ->orWhere('nip', 'like', '%' . $data['filter']['search'] . '%');
            });
        })->when(!$data['filter']['search'], function ($query) {
            $query->whereHas('peserta');
        })->when($data['filter']['status'], function ($query) use ($data) {
            $query->where('status', $data['filter']['status']);
        })->when($data['filter']['predikat'], function ($query) use ($data) {
            $query->where('predikat', $data['filter']['predikat']);
        })->with('peserta:nip,nama_lengkap')->orderByDesc('id')->paginate($data['filter']['limit'])->appends($data['filter']);

        return Inertia::render("Hasil/Index", $data);
    }
    public function create()
    {
        return Excel::download(new TemplateHasilExport(), 'Template Import Data Hasil Pelatihan.xlsx');
    }
    public function store(Request $request)
    {
        $data = $request->validate(['file' => 'required|file|mimes:xlsx,xls|max:2048']);
        Excel::import(new PelatihanImport(), $data['file']);
        $request->session()->flash('Berhasil mengimport data');
        return back();
    }
}
