<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanPelatihan extends Model
{
    use HasFactory;

    protected $table = 'silat_bahan_pelatihan';

    protected $fillable = [
        'pelatihan_id',
        'name',
        'file',
    ];
}
