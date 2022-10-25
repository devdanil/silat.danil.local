<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesertaExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
  /**
   * @return \Illuminate\Support\Collection
   */

  protected $pelatihan;

  function __construct($pelatihan)
  {
    $this->pelatihan = $pelatihan;
  }

  public function collection()
  {
    $peserta = Pendaftaran::select('nip')->where('pelatihan_id', $this->pelatihan->id)->when($this->pelatihan->status_id == 6, function ($query) {
      $query->whereNotNull('approved_at');
    })->when(in_array($this->pelatihan->status_id, [4, 5]), function ($query) {
      $query->whereNotNull('registered_at');
    })->with('peserta', function ($query) {
      $query->select('nip', 'nama_lengkap', 'gelar_depan', 'gelar_belakang', 'tempat_lahir',  'kd_jabatan', 'kd_golongan', 'nama_dinas', 'unit_kerja', 'lokasi_dinas', 'id_kota_rumah', 'id_provinsi_rumah', 'pangkat')->with('jabatan:kd_jabatan,jabatan', 'asalKota:id,nama', 'asalProv:id,nama', 'golongan:golongan,pangkat');
    })->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->orderBy('registered_at', 'asc')->get();

    $this->columns = 10;
    $this->rows = $peserta->count() + 1;
    return $peserta;
  }

  public function headings(): array
  {
    return [
      'Nama', 'NIP', 'Tempat, Tanggal Lahir', 'Pangkat, Golongan Ruang', 'Jabatan', 'Instansi Kerja', 'Nama Pelatihan', 'Tanggal Pelaksanaan',  'Asal Kab/Kota', 'Provinsi'
    ];
  }

  public function map($item): array
  {
    $tgl_lahir = '';
    $months = [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ];
    try {
      $tahun = substr($item->peserta->nip, 0, 4);
      $bulan = intval(substr($item->peserta->nip, 4, 2));
      $tgl = substr($item->peserta->nip, 6, 2);
      $tgl_lahir = $tgl . ' ' . $months[$bulan - 1] . ' ' . $tahun;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [
      ($item->peserta->gelar_depan ? $item->peserta->gelar_depan . '. ' : '') . $item->peserta->nama_lengkap . ($item->peserta->gelar_belakang ? ', ' . $item->peserta->gelar_belakang : ''), " " . $item->nip, $item->peserta->tempat_lahir . ', ' . $tgl_lahir, ($item->peserta->golongan ? $item->peserta->golongan->pangkat . ', ' . '(' . $item->peserta->golongan->golongan . ')' : $item->peserta->pangkat), $item->peserta->jabatan->jabatan, ($item->peserta->lokasi_dinas == "uml" ? $item->peserta->nama_dinas : $item->peserta->unit_kerja), $this->pelatihan->katalog->judul, $this->pelatihan->mulai_pelatihan . ' - ' . $this->pelatihan->selesai_pelatihan, $item->peserta->asalKota ? $item->peserta->asalKota->nama : '-', $item->peserta->asalProv ? $item->peserta->asalProv->nama : '-'
    ];
  }

  public function styles(Worksheet $sheet)
  {

    $alpha = range('A', 'Z');
    $col = $alpha[($this->columns - 1)];

    for ($i = 0; $i < $this->columns; $i++) {
      $sheet->getColumnDimension($alpha[$i])->setAutoSize(true);
    }
    $sheet->getRowDimension("1")->setRowHeight(20, 'pt');

    $sheet->getStyle('A1:' . $col . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->getStyle('A1:' . $col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2F3B59');

    $sheet->getStyle('A1:' . $col . '1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

    $sheet->getStyle('A1:' . $col . $this->rows)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
  }
}
