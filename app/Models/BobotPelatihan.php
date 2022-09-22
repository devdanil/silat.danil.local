<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotPelatihan extends Model
{
    use HasFactory;

    protected $table = 'silat_bobot_pelatihan';

    protected $fillable = [
        'pelatihan_id',
        'key',
        'bobot',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'key', 'kd_jabatan');
    }
}
