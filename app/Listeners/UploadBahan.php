<?php

namespace App\Listeners;

use App\Models\KatalogBahan;

class UploadBahan
{

    public function handle($event)
    {
        if ($event->bahan) {
            $data = [];
            foreach ($event->bahan as $key => $file) {
                $file->store('public/bahan/');
                $data[] = [
                    'katalog_id' => $event->katalog->id,
                    'name' => $file->getClientOriginalName(),
                    'file' => asset('storage/bahan/' . $file->hashName())
                ];
            }
            KatalogBahan::insert($data);
        }
    }
}
