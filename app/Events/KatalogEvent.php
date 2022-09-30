<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KatalogEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $katalog;
    public $jabatan;
    public $bahan;

    public function __construct($katalog, $jabatan, $bahan)
    {
        $this->katalog = $katalog;
        $this->jabatan = $jabatan;
        $this->bahan = $bahan;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
