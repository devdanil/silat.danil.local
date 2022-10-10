<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

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
}
