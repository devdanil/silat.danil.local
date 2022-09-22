<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        })->paginate($data['filter']['limit'])->appends($data['filter']);
        return Inertia::render("User/Index", $data);
    }
    public function create()
    {
        $data['title'] = "Tambah User";
        return Inertia::render("User/Create", $data);
    }
}
