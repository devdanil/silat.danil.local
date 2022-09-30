<?php

namespace App\Listeners;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;

class StorePendaftaran
{

    public function handle($event)
    {
        if ($event->pelatihan->status_id == 6) {
            $pendaftaran = Pendaftaran::whereNotNull('confirmed_at')->whereNull('rejected_at')->where('pelatihan_id', $event->pelatihan->id)->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->limit($event->pelatihan->kuota)->get('id');
            Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['approved_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
            Pendaftaran::whereNull('confirmed_at')->where('pelatihan_id', $event->pelatihan->id)->update(['rejected_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
        }
    }
}
