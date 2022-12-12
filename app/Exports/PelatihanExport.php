<?php

namespace App\Exports;

use App\Models\Pelatihan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelatihanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        $pelatihan = Pelatihan::whereYear($this->data['key_year'], $this->data['year'])->whereHas('katalog.jabatan')->with(['katalog.jabatan.jabatan', 'katalog.bahan'])->orderByDesc('id')->get();
        $this->columns = 12;
        $this->rows = $pelatihan->count() + 1;
        return $pelatihan;
    }
    public function headings(): array
    {
        return ['Jenis Pelatihan', 'Keterangan Jabatan', 'Judul Pelatihan', 'Deskripsi', 'Silabus', 'Persyaratan Peserta', 'Jenis JF yang dapat mengikuti', 'Instansi Peserta Pelatihan', 'Kuota Peserta', 'Tanggal Pendaftaran', 'Tanggal Pelatihan', 'Batas Konfirmasi'];
    }

    public function map($item): array
    {
        $jenis_jf = '';
        foreach ($item->katalog->jabatan as $key) {
            $jenis_jf .= $key->jabatan->jabatan . "\n";
        }
        $ket_jabatan = '';
        if ($item->katalog->jenis_pelatihan == 'fungsional' && $item->katalog->ket_jabatan) {
            foreach (json_decode($item->katalog->ket_jabatan) as $key => $value) {
                $ket_jabatan .= $value . "\n";
            }
        }
        return ['Pelatihan ' . ucfirst($item->katalog->jenis_pelatihan), $ket_jabatan, $item->katalog->judul, strip_tags($item->katalog->deskripsi), strip_tags($item->katalog->silabus), strip_tags($item->katalog->persyaratan), $jenis_jf, $item->katalog->instansi, $item->kuota, Carbon::parse($item->mulai_pendaftaran)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($item->selesai_pendaftaran)->translatedFormat('d F Y'), Carbon::parse($item->mulai_pelatihan)->translatedFormat('d F Y') . ' s.d ' . Carbon::parse($item->selesai_pelatihan)->translatedFormat('d F Y'), Carbon::parse($item->batas_konfirmasi)->translatedFormat('d F Y')];
    }

    public function styles(Worksheet $sheet)
    {

        $alpha = range('A', 'Z');
        $col = $alpha[($this->columns - 1)];

        $sheet->getRowDimension("1")->setRowHeight(20, 'pt');

        $sheet->getStyle('A1:' . $col . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A1:' . $col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2F3B59');

        $sheet->getStyle('A1:' . $col . '1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

        $sheet->getStyle('A1:' . $col . $this->rows)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
