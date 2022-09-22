<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'silat_users';
    protected $fillable = [
        'nama',
        'email',
        'nip',
        'foto',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    public function hasRolesID($rolesID)
    {
        return $this->roles()->whereIn('role_id', $rolesID)->count() > 0;
    }

    public function getRouteKeyName()
    {
        return 'nip';
    }
}
