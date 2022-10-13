<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Pelatihan;
use Maatwebsite\Excel\Facades\Excel;

class PesertaController extends Controller
{
    public function export(Pelatihan $pelatihan)
    {
        return Excel::download(new PesertaExport($pelatihan), 'Daftar Peserta Pelatihan ' . $pelatihan->katalog->judul . '.xlsx');
    }
}
