<?php

namespace App\Policies;

use App\Models\Hasil;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HasilPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRolesID([1]);
    }


    public function create(User $user)
    {
        return $user->hasRolesID([1]);
    }
}
