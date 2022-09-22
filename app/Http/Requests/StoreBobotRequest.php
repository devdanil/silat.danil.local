<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBobotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRolesID([2]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'R-02-01' => ['numeric', 'max:100'],
            'R-02-02' => ['numeric', 'max:100'],
            'R-02-03' => ['numeric', 'max:100'],
            'R-04-01' => ['numeric', 'max:100'],
            'R-04-02' => ['numeric', 'max:100'],
            'R-04-03' => ['numeric', 'max:100'],
            'R-04-04' => ['numeric', 'max:100'],
            'R-01-01' => ['numeric', 'max:100'],
            'R-01-02' => ['numeric', 'max:100'],
            'R-01-03' => ['numeric', 'max:100'],
            'R-03-01' => ['numeric', 'max:100'],
            'R-03-02' => ['numeric', 'max:100'],
            'R-03-03' => ['numeric', 'max:100'],
            'R-05-01' => ['numeric', 'max:100'],
            'R-05-02' => ['numeric', 'max:100'],
            'R-05-03' => ['numeric', 'max:100'],
            'tidak_pelatihan' => ['required', 'max:100'],
            'ikut_pelatihan' => ['required', 'max:100'],
            'status_id' => ['numeric', 'max:5', 'min:2']
        ];
    }
}
