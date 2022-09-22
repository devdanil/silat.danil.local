<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'silat_menus';

    public function roles()
    {
        return $this->hasMany(RoleMenu::class, 'menu_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
