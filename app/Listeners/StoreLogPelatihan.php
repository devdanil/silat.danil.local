<?php

namespace App\Listeners;

use App\Mail\ExtraRegistrationPelatihan as MailExtraRegistrationPelatihan;
use App\Models\LogPelatihan;
use App\Models\Peserta;
use App\Notifications\ExtraRegistrationPelatihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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
        if ($event->pelatihan->keterangan == 'perpanjangan pendaftaran') {
            $peserta = Peserta::select('email')->whereNotNull('email')->whereHas('pendaftaran', function ($query) use ($data) {
                $query->where('pelatihan_id', $data['pelatihan_id'])->whereNull('registered_at')->whereNull('rejected_at');
            })->get();
            // $peserta = Peserta::select('email')->whereIn('email', ['devdanil14@gmail.com', 'danilstmik14@gmail.com'])->get();
            // if ($peserta) {
            //     // Notification::send($peserta, new ExtraRegistrationPelatihan($event->pelatihan));
            //     Mail::to('danilpitopang02@gmail.com')->bcc($peserta)->send(new MailExtraRegistrationPelatihan($event->pelatihan));
            // }
        }
    }
}
