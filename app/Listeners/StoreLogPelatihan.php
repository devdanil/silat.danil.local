<?php

namespace App\Listeners;

use App\Models\LogPelatihan;
use Illuminate\Support\Facades\Auth;

class StoreLogPelatihan
{
    public function handle($event)
    {
        $data = [
            'pelatihan_id' => $event->pelatihan->id,
            'status_id' => $event->pelatihan->status_id,
            'keterangan' => $event->pelatihan->keterangan,
            'user_id' => Auth::id(),
        ];
        LogPelatihan::create($data);
    }
}
