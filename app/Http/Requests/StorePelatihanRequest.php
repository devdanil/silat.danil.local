<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelatihanRequest extends FormRequest
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
            'katalog_id' => ['required'],
            'mulai_pelatihan' => ['required', 'date'],
            'selesai_pelatihan' => ['required', 'date'],
            'mulai_pendaftaran' => ['required', 'date'],
            'selesai_pendaftaran' => ['required', 'date'],
            'status_id' => ['required', 'numeric', 'max:2', 'min:1'],
            'kuota' => ['required', 'numeric'],
        ];
    }
}
