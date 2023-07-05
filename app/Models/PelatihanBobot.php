<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanBobot extends Model
{
    use HasFactory;
    protected $table = 'silat_pelatihan_bobot';

    protected $fillable = [
        'pelatihan_id',
        'key',
        'bobot',
        'kd_jabatan',
        'nilai',
        'kabkota_id',
        'jumlah_peserta'
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kd_jabatan', 'kd_jabatan');
    }
    public function variable()
    {
        return $this->belongsTo(Variable::class, 'key', 'key');
    }
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'kd_jabatan', 'key');
    }
    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kabkota_id', 'id');
    }
}
