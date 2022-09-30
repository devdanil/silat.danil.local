<?php

namespace App\Policies;

use App\Models\Katalog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KatalogPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->hasRolesID([1]);
    }

    public function view(User $user, Katalog $katalog)
    {
        return $user->hasRolesID([1]);
    }

    public function create(User $user)
    {
        return $user->hasRolesID([1]);
    }

    public function update(User $user, Katalog $katalog)
    {
        return $user->hasRolesID([1]);
    }

    public function delete(User $user, Katalog $katalog)
    {
        return $user->hasRolesID([1]);
    }
}
