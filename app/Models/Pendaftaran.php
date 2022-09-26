<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'silat_pendaftaran';

    protected $fillable = [
        'nip',
        'pelatihan_id',
        'jumlah_bobot',
        'confirmed_at',
        'rejected_at',
        'approved_at',
        'created_by',
        'updated_by',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'nip', 'nip');
    }
}