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
        $peserta = Pendaftaran::select('nip')->where('pelatihan_id', $this->pelatihan->id)->whereNotNull('approved_at')->with('peserta', function ($query) {
            $query->select('nip', 'nama_lengkap', 'kd_jabatan', 'id_kota_rumah', 'id_provinsi_rumah')->with('jabatan:kd_jabatan,jabatan', 'asalKota:id,nama', 'asalProv:id,nama');
        })->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->get();

        $this->columns = 7;
        $this->rows = $peserta->count() + 1;
        return $peserta;
    }

    public function headings(): array
    {
        return [
            'NIP', 'Nama', 'Jabatan', 'ID Pelatihan', 'Nama Pelatihan', 'Asal Kab/Kota', 'Provinsi'
        ];
    }

    public function map($item): array
    {
        return [
            " " . $item->nip, $item->peserta->nama_lengkap, $item->peserta->jabatan->jabatan, " " . $this->pelatihan->id, $this->pelatihan->katalog->judul, $item->peserta->asalKota ? $item->peserta->asalKota->nama : '-', $item->peserta->asalProv ? $item->peserta->asalProv->nama : '-'
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
