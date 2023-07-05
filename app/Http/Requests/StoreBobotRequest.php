<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBobotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRolesID([2, 3]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => ['required', $this->key != 'kabkota' ? Rule::unique('silat_pelatihan_bobot')->where(function ($query) {
                return $query->where('pelatihan_id', $this->pelatihan_id);
            })->ignore($this->id) : ''],
            'bobot' => ['required', 'numeric', 'max:100'],
            'kd_jabatan' => ['required_if:key,angka_kredit', 'required_if:key,prestasi'],
            'kabkota_id' => ['required_if:key,kabkota', 'required_if:key,prestasi'],
            'nilai' => ['required_if:key,angka_kredit'],

            'pelatihan_id' => ['required', 'numeric']
        ];
    }
    public function messages()
    {
        return [
            'key.unique' => 'Kriteria bobot yang dipilih sudah ada',
        ];
    }
}
