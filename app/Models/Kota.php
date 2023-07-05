<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;
    protected $table = 'mst_kota';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id');
    }
}
