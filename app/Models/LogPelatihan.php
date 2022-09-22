<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPelatihan extends Model
{
    use HasFactory;

    protected $table = 'silat_log_pelatihan';

    protected $fillable = ['pelatihan_id', 'status_id', 'keterangan', 'user_id'];
}
