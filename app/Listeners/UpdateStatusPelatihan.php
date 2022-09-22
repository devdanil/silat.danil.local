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
        $data['updated_by'] = Auth::id();
        $event->pelatihan->update($data);
    }
}
