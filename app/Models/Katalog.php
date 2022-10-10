<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'silat_katalog';

    protected $fillable = [
        'judul',
        'jenis_pelatihan',
        'syarat_katalog',
        'ket_jabatan',
        'instansi',
        'slug',
        'deskripsi',
        'silabus',
        'persyaratan',
        'is_publish',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function pelatihan()
    {
        return $this->hasMany(Pelatihan::class, 'katalog_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function jabatan()
    {
        return $this->hasMany(KatalogJabatan::class, 'katalog_id', 'id');
    }

    public function bahan()
    {
        return $this->hasMany(KatalogBahan::class, 'katalog_id', 'id');
    }

    public function bobot()
    {
        return $this->hasMany(KatalogBobot::class, 'katalog_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(LogPelatihan::class, 'katalog_id', 'id');
    }
}
