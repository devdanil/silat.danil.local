<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    use HasFactory;

    protected $table = 'silat_hasil';

    protected $fillable = [
        'pelatihan_id',
        'jenis_pelatihan',
        'nip',
        'nik',
        'no_sertifikat',
        'tgl_sertifikat',
        'total_hari',
        'total_jam',
        'nilai',
        'status',
        'predikat',
        'created_by',
        'updated_by',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'nip', 'nip');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id', 'id');
    }
}
