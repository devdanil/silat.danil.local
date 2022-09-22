<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'tbk_jabatan';

    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'kd_jabatan', 'kd_jabatan');
    }
}
