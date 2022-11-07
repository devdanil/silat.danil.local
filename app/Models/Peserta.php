<?php

namespace App\Models;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Peserta extends Model implements ShouldQueue
{
    use HasFactory, Notifiable;

    protected $table = 'tbm_pegawai';

    public function riwayatPelatihan()
    {
        return $this->hasMany(RiwayatPelatihan::class, 'nip', 'nip');
    }
    public function angker() // angka kredit
    {
        return $this->hasMany(AngkaKredit::class, 'nip', 'nip');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'kd_jabatan', 'kd_jabatan');
    }
    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'kd_golongan', 'golongan');
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'nip', 'nip');
    }

    public function asalKota()
    {
        return $this->belongsTo(Kota::class, 'id_kota_rumah', 'id');
    }

    public function asalProv()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi_rumah', 'id');
    }
}
