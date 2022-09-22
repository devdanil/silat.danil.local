<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $table = 'silat_users_roles';

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
