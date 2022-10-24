<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PesertaController extends Controller
{
  public function export(Pelatihan $pelatihan)
  {
    return Excel::download(new PesertaExport($pelatihan), 'Daftar ' . (in_array($pelatihan->status_id, [4, 5]) ? 'Calon' : "") . ' Peserta Pelatihan ' . $pelatihan->katalog->judul . '.xlsx');
  }
}
