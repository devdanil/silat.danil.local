<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateHasilExport implements WithHeadings, WithEvents, ShouldAutoSize
{

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $row_count = 101;
                $drop_column = ['C', 'K', 'L'];
                $options = [
                    "C" =>
                    [
                        '',
                        'fungsional',
                        'teknis',
                        'mansoskul',
                    ], "K" => [
                        '',
                        'Lulus',
                        'Tidal Lulus',
                    ], "L" => [
                        '',
                        'Sangat Baik',
                        'Baik',
                        'Cukup',
                        'Kurang',
                        'Sangat Kurang',
                    ]
                ];
                foreach ($drop_column as $key => $value) {
                    $validation = $event->sheet->getCell("{$value}2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input error');
                    $validation->setError('Value is not in list.');
                    $validation->setPromptTitle('Pick from list');
                    $validation->setPrompt('Please pick a value from the drop-down list.');
                    $validation->setFormula1(sprintf('"%s"', implode(',', $options[$value])));
                    for ($i = 3; $i <= $row_count; $i++) {
                        $event->sheet->getCell("{$value}{$i}")->setDataValidation(clone $validation);
                    }
                }
                $alpha = range('A', 'Z');
                $col = $alpha[11];

                $event->sheet->getRowDimension("1")->setRowHeight(20, 'pt');

                $event->sheet->getStyle('A1:' . $col . '1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getStyle('A1:' . $col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('2F3B59');

                $event->sheet->getStyle('A1:' . $col . '1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

                $event->sheet->getStyle('A1:' . $col . $row_count)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
    public function headings(): array
    {
        return [
            'No.', 'ID Pelatihan (Jika Pelatihan dari Si Pelatihan)', 'Jenis Pelatihan', 'NIP', 'NIK', 'No. Sertifikat', 'Tanggal Sertifikat', 'Lama Mengikuti (Hari)', 'Total Jam Pelajaran', 'Nilai', 'Status', 'Predikat'
        ];
    }
}
