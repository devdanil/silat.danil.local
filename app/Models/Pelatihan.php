<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'silat_pelatihan';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'silabus',
        'persyaratan',
        'tgl_mulai',
        'tgl_selesai',
        'mulai_pendaftaran',
        'selesai_pendaftaran',
        'batas_konfirmasi',
        'kuota',
        'is_publish',
        'status_id',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function jabatan()
    {
        return $this->hasMany(JabatanPelatihan::class, 'pelatihan_id', 'id');
    }

    public function bahan()
    {
        return $this->hasMany(BahanPelatihan::class, 'pelatihan_id', 'id');
    }

    public function bobot()
    {
        return $this->hasMany(BobotPelatihan::class, 'pelatihan_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(LogPelatihan::class, 'pelatihan_id', 'id');
    }
}
