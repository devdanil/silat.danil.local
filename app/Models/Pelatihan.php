<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
  use HasFactory;

  protected $table = 'silat_pelatihan';

  protected $fillable = [
    'katalog_id',
    'slug',
    'mulai_pelatihan',
    'selesai_pelatihan',
    'mulai_pendaftaran',
    'selesai_pendaftaran',
    'batas_konfirmasi',
    'kuota',
    'status_id',
    'keterangan',
    'created_by',
    'updated_by',
    'surat_pemanggilan'
  ];

  public function getRouteKeyName()
  {
    return 'slug';
  }
  public function katalog()
  {
    return $this->belongsTo(Katalog::class, 'katalog_id', 'id');
  }
  public function pendaftaran()
  {
    return $this->hasMany(Pendaftaran::class, 'pelatihan_id', 'id');
  }
  public function status()
  {
    return $this->belongsTo(Status::class, 'status_id', 'id');
  }
  public function bobot()
  {
    return $this->hasMany(PelatihanBobot::class, 'pelatihan_id', 'id');
  }
  public function logs()
  {
    return $this->hasMany(LogPelatihan::class, 'pelatihan_id', 'id');
  }
}
