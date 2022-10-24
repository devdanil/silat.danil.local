<?php

namespace App\Http\Requests;

use App\Models\Katalog;
use App\Models\Pelatihan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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

  public function validation($pelatihan_id = null)
  {
    $katalog = Katalog::find($this->katalog_id);
    if ($katalog && $katalog->jenis_pelatihan == 'teknis') {
      $check = Pelatihan::whereHas('katalog', function ($query) {
        $query->where('jenis_pelatihan', 'teknis');
      })->whereBetween('mulai_pelatihan', [$this->mulai_pelatihan, $this->selesai_pelatihan])
        ->when($pelatihan_id, function ($query) use ($pelatihan_id) {
          $query->where('id', '!=', $pelatihan_id);
        })
        ->orWhereBetween('selesai_pelatihan', [$this->mulai_pelatihan, $this->selesai_pelatihan])->when($pelatihan_id, function ($query) use ($pelatihan_id) {
          $query->where('id', '!=', $pelatihan_id);
        })->count();
      if ($check > 2) {
        throw ValidationException::withMessages([
          'mulai_pelatihan' => "Pelatihan teknis pada waktu pelatihan yang dipilih sudah penuh, silahkan ganti tanggal pelatihan.",
        ]);
        return back();
      }
    }
    return $this->validated();
  }
}
