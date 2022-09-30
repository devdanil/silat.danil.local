<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogBahan extends Model
{
    use HasFactory;

    protected $table = 'silat_katalog_bahan';

    protected $fillable = [
        'katalog_id',
        'name',
        'file',
    ];
}
