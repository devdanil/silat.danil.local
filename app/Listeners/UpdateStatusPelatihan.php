<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class UpdateStatusPelatihan
{

  public function handle($event)
  {
    $data = $event->data;
    $data['keterangan'] = isset($data['keterangan']) ? $data['keterangan'] : null;
    if (isset($data['surat_pemanggilan'])) {
      $data['surat_pemanggilan']->store('public/surat/pemanggilan/');
      $data['surat_pemanggilan'] = asset('storage/surat/pemanggilan/' .  $data['surat_pemanggilan']->hashName());
    }
    $data['updated_by'] = Auth::id();
    $event->pelatihan->update($data);
  }
}
