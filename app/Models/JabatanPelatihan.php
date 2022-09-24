<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanPelatihan extends Model
{
    use HasFactory;

    protected $table = 'silat_jabatan_pelatihan';

    protected $fillable = ['pelatihan_id', 'kd_jabatan'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kd_jabatan', 'kd_jabatan');
    }
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'kd_jabatan', 'kd_jabatan');
    }
}
