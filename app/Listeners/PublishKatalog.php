<?php

namespace App\Listeners;

use App\Mail\PublishKatalog as MailPublishKatalog;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Notifications\PublishedKatalog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class PublishKatalog
{

    public function handle($event)
    {
        if ($event->katalog->is_publish) {
            $katalogs = $event->katalog->syarat_katalog ? json_decode($event->katalog->syarat_katalog) : null;
            $pelatihans = $katalogs ? Pelatihan::whereIn('katalog_id', $katalogs)->get(['id']) : null;
            $instansi = $event->katalog->instansi;
            $ket_jabatan = json_decode($event->katalog->ket_jabatan);
            $peserta =  Peserta::select('nip', 'kd_jabatan', 'email')->whereIn('kd_jabatan',  $event->katalog->jabatan()->pluck('kd_jabatan')->all())->when($event->katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
                $query->whereIn('keterangan_jbt', $ket_jabatan);
            })->when($event->katalog->instansi != 'pusat_uml', function ($query) use ($instansi) {
                $query->where('lokasi_dinas', $instansi);
            })->when($pelatihans, function ($query) use ($pelatihans) {
                $query->whereHas('pendaftaran', function ($query2) use ($pelatihans) {
                    $query2->whereIn('pelatihan_id', $pelatihans->pluck('id')->all());
                });
            })->orderBy('nama_lengkap', 'ASC')->get();
            // $peserta = Peserta::select('email')->whereIn('email', ['devdanil14@gmail.com', 'danilstmik14@gmail.com'])->get();
            // // Notification::send($peserta, new PublishedKatalog($event->katalog));
            // Mail::to('danilpitopang02@gmail.com')->bcc($peserta)->send(new MailPublishKatalog($event->katalog));
        }
    }
}
