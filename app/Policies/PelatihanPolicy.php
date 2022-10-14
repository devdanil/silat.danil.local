<?php

namespace App\Policies;

use App\Models\Pelatihan;
use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PelatihanPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->hasRolesID([1, 2, 3]);
    }

    public function view(User $user, Pelatihan $pelatihan)
    {
        return $user->hasRolesID([1, 2, 3]);
    }

    public function create(User $user)
    {
        return $user->hasRolesID([1]);
    }

    public function update(User $user, Pelatihan $pelatihan)
    {
        return $user->hasRolesID([$pelatihan->status->role_id]) && in_array($pelatihan->status_id, [1, 7]);
    }

    public function delete(User $user, Pelatihan $pelatihan)
    {
        return $user->hasRolesID([$pelatihan->status->role_id]) && in_array($pelatihan->status_id, [1, 7]);
    }

    public function process(User $user, Pelatihan $pelatihan, $status_id)
    {
        return ($user->hasRolesID([$pelatihan->status->role_id]) && in_array($status_id, [$pelatihan->status->next_id, $pelatihan->status->prev_id])) || ($user->hasRolesID([1]) && ($status_id == 5 || $status_id == 8));
    }
}
