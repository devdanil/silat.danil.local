<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KatalogRequest extends FormRequest
{
  public function authorize()
  {
    return $this->user()->hasRolesID([1]);
  }


  public function rules()
  {
    return [
      'judul' => ['required', 'max:255'],
      'jenis_pelatihan' => ['required', 'max:50'],
      'ket_jabatan' => ['required_if:jenis_pelatihan,fungsional'],
      'syarat_katalog' => [''],
      'instansi' => ['required', 'max:50'],
      'deskripsi' => ['required'],
      'silabus' => ['required'],
      'persyaratan' => ['required'],
      'is_publish' => ['boolean'],
      'kd_jabatan' => ['required', 'array'],
      'file_bahan' => ['array'],
      'file_bahan.*' => ['mimes:pdf,xlsx,xls,docx,doc', 'file', 'max:2048'],
      'img' => $this->img ? ['image', 'mimes:jpg,jpeg,png', 'max:2048'] : ['max:2048']
    ];
  }
}
