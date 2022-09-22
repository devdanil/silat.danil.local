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
            'judul' => ['required', 'max:255'],
            'deskripsi' => ['required'],
            'silabus' => ['required'],
            'persyaratan' => ['required'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date'],
            'mulai_pendaftaran' => ['required_unless:tgl_mulai,null', 'date'],
            'selesai_pendaftaran' => ['required_unless:tgl_selesai,null', 'date'],
            'kuota' => ['required', 'numeric'],
            'status_id' => ['numeric', 'max:2', 'min:1'],
            'is_publish' => ['boolean'],
            'kd_jabatan' => ['required', 'array'],
            'file_bahan' => ['array'],
            'file_bahan.*' => ['mimes:pdf,xlsx,xls,docx,doc', 'file', 'max:2048']
        ];
    }
}
