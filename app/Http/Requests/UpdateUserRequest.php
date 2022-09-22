<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRolesID([1]);
    }


    public function rules()
    {
        return [
            'nama' => ['required', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'role_id' => ['required'],
            'foto' => $this->foto ? ['image', 'mimes:jpg,jpeg,png', 'max:2048'] : [],
        ];
    }
}
