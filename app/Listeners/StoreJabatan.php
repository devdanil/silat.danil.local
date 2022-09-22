<?php

namespace App\Listeners;

use App\Events\CreatePelatihan;
use App\Models\JabatanPelatihan;

class StoreJabatan
{

    public function handle(CreatePelatihan $event)
    {
        JabatanPelatihan::where('pelatihan_id', $event->pelatihan->id)->delete();
        $data = [];
        foreach ($event->jabatan as $key => $value) {
            $data[] = ['pelatihan_id' => $event->pelatihan->id, 'kd_jabatan' => $value];
        }
        JabatanPelatihan::insert($data);
    }
}
