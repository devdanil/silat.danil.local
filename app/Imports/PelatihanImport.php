<?php

namespace App\Imports;

use App\Models\Hasil;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PelatihanImport implements ToModel, WithBatchInserts, WithStartRow, WithValidation
{
    public function model(array $row)
    {
        return new Hasil([

            'pelatihan_id' => is_numeric($row[1]) && Pelatihan::find($row[1]) ? $row[1] : null,
            'jenis_pelatihan' => $row[2],
            'nip' => is_numeric($row[3]) && Peserta::firstWhere('nip', $row[3]) ? $row[3] : null,
            'nik' => $row[4],
            'no_sertifikat' => $row[5],
            'tgl_sertifikat' => $row[6],
            'total_hari' => $row[7],
            'total_jam' => $row[8],
            'nilai' => $row[9],
            'status' => $row[10],
            'predikat' => $row[11],
            'created_by' => Auth::id(),
            'updated_by' =>  Auth::id(),

        ]);
    }
    public function batchSize(): int
    {
        return 1000;
    }
    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [
            '*.1' =>  ['required'],
            '*.3' =>  ['required'],
            '*.4' =>  ['required'],
            '*.5' =>  ['required'],
            '*.6' =>  ['required', 'date'],
            '*.7' =>  ['required', 'numeric'],
            '*.8' =>  ['required', 'numeric'],
            '*.9' =>  ['required'],
            '*.10' =>  ['required'],
            '*.11' =>  ['required'],
        ];
    }
}
