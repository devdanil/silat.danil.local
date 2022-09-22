<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRolesID([1]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nip' => ['required', 'unique:silat_users', 'max:30'],
            'nama' => ['required', 'max:150'],
            'email' => ['required', 'unique:silat_users', 'email', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'password_confirmation' => ['required'],
            'role_id' => ['required'],
            'foto' => $this->foto ? ['image', 'mimes:jpg,jpeg,png', 'max:2048'] : [],
        ];
    }
}
