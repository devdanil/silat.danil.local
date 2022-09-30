<?php

namespace App\Listeners;

use App\Models\KatalogJabatan;

class StoreJabatan
{

    public function handle($event)
    {
        KatalogJabatan::where('katalog_id', $event->katalog->id)->delete();
        $data = [];
        foreach ($event->jabatan as $key => $value) {
            $data[] = ['katalog_id' => $event->katalog->id, 'kd_jabatan' => $value];
        }
        KatalogJabatan::insert($data);
    }
}
