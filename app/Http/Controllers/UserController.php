<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = "Users";
        $data['filter'] = [
            'limit' => $request->get('limit') ?? 10,
            'search' => $request->get('search'),
        ];
        $data['list'] = User::with('roles.role')->when($data['filter']['search'], function ($query) use ($data) {
            $query->where('nama', 'like', '%' . $data['filter']['search'] . '%')
                ->orWhere('nip', 'like', '%' . $data['filter']['search'] . '%')
                ->orWhere('email', 'like', '%' . $data['filter']['search'] . '%');
        })->orderByDesc('id')->paginate($data['filter']['limit'])->appends($data['filter']);
        return Inertia::render("User/Index", $data);
    }
    public function create()
    {
        $data['title'] = "Tambah User";
        $data['roles'] = Role::all(['id', 'name']);
        return Inertia::render("User/Create", $data);
    }

    public function store(StoreUserRequest $request)
    {
        $data =  $request->safe()->except(['role_id', 'foto']);
        $foto = $request->file('foto');
        if ($foto) {
            $foto->store('public/users/');
            $data['foto'] = asset('storage/users/' . $foto->hashName());
        }
        $user = User::create($data);
        UserRole::create(['user_id' => $user->id, 'role_id' => $request->post('role_id'), 'created_by' => Auth::id(), 'updated_by' => Auth::id()]);
        $request->session()->flash('flash.msg', "Pengguna berhasil tambah!");
        $request->session()->flash('flash.error', false);
        return Redirect::route('users.index');
    }

    public function edit(User $user)
    {
        $data['title'] = "Ubah User";
        $data['user'] = $user;
        $data['user_roles'] = $user->roles()->pluck('role_id')->all();
        $data['roles'] = Role::all(['id', 'name']);
        return Inertia::render("User/Edit", $data);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->safe()->except(['role_id', 'foto']);
        $foto = $request->file('foto');
        if ($foto) {
            $foto->store('public/users/');
            $data['foto'] = asset('storage/users/' .  $foto->hashName());
        }
        if (User::where('email', $data['email'])->where('id', '!=', $user->id)->count() > 0) {
            return back()->with(['flash.msg' => "Isian email sudah digunakan", 'flash.error', true]);
        }
        $user->update($data);
        UserRole::updateOrInsert(['user_id' => $user->id], ['role_id' => $request->post('role_id'), 'updated_by' => Auth::id()]);
        $request->session()->flash('flash.msg', "Pengguna berhasil diubah!");
        $request->session()->flash('flash.error', false);
        return Redirect::route('users.index');
    }

    public function destroy(User $user)
    {
        $user->roles()->delete();
        $user->delete();
        return back();
    }
}
