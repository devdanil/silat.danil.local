<?php

namespace App\Listeners;

use App\Events\CreatePelatihan;
use App\Models\BahanPelatihan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UploadBahan
{

    public function handle(CreatePelatihan $event)
    {
        if ($event->bahan) {
            $data = [];
            foreach ($event->bahan as $key => $file) {
                $file->store('public/bahan/');
                $data[] = [
                    'pelatihan_id' => $event->pelatihan->id,
                    'name' => $file->getClientOriginalName(),
                    'file' => asset('storage/bahan/' . $file->hashName())
                ];
            }
            BahanPelatihan::insert($data);
        }
    }
}
