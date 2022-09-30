<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogJabatan extends Model
{
    use HasFactory;

    protected $table = 'silat_katalog_jabatan';

    protected $fillable = ['katalog_id', 'kd_jabatan'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kd_jabatan', 'kd_jabatan');
    }
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'kd_jabatan', 'kd_jabatan');
    }
}
