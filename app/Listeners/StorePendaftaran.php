<?php

namespace App\Listeners;

use App\Mail\ApprovePeserta;
use App\Mail\ConfirmationPeserta as MailConfirmationPeserta;
use App\Mail\SubmitPelatihan;
use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\RiwayatPelatihan;
use App\Notifications\ApprovedPeserta;
use App\Notifications\ConfirmationPeserta;
use App\Notifications\SubmittedPelatihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class StorePendaftaran
{

  public function handle($event)
  {
    if ($event->pelatihan->status_id == 2) {
      $katalog = $event->pelatihan->katalog;
      $katalogs = $katalog->syarat_katalog ? json_decode($katalog->syarat_katalog) : null;
      $pelatihans = $katalogs ? Pelatihan::whereIn('katalog_id', $katalogs)->get(['id']) : null;
      $instansi = $katalog->instansi;
      $ket_jabatan = json_decode($katalog->ket_jabatan);
      $peserta =  Peserta::select('nip', 'kd_jabatan', 'email')->whereIn('kd_jabatan',  $katalog->jabatan()->pluck('kd_jabatan')->all())->when($katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
        $query->whereIn('keterangan_jbt', $ket_jabatan);
      })->when($katalog->instansi != 'pusat_uml', function ($query) use ($instansi) {
        $query->where('lokasi_dinas', $instansi);
      })->when($pelatihans, function ($query) use ($pelatihans) {
        $query->whereHas('pendaftaran', function ($query2) use ($pelatihans) {
          $query2->whereIn('pelatihan_id', $pelatihans->pluck('id')->all());
        });
      })->orderBy('nama_lengkap', 'ASC')->get();

      $data = [];
      Pendaftaran::where('pelatihan_id', $event->pelatihan->id)->delete();
      foreach ($peserta as $key) {
        $data[] = [
          'nip' => $key->nip,
          'pelatihan_id' =>  $event->pelatihan->id,
          'jumlah_bobot' => 0,
          'created_by' => Auth::id(),
          'updated_by' => Auth::id(),
        ];
      }
      Pendaftaran::insert($data);
      // $peserta = Peserta::select('email')->whereIn('email', ['devdanil14@gmail.com', 'danilstmik14@gmail.com'])->get();
      // if ($peserta) {
      //   // Notification::send($peserta, new SubmittedPelatihan($event->pelatihan));
      //   Mail::to('danilpitopang02@gmail.com')->bcc($peserta)->send(new SubmitPelatihan($event->pelatihan));
      // }
    } else if (in_array($event->pelatihan->status_id, [4, 5]) && $event->pelatihan->selesai_pendaftaran <= date('Y-m-d')) {
      $pendaftaran = Pendaftaran::whereNotNull('registered_at')->whereNull('rejected_at')->whereNull('sendmail_at')->where('pelatihan_id', $event->pelatihan->id)->orderByDesc('jumlah_bobot')->orderBy('registered_at', 'asc')->limit($event->pelatihan->kuota)->get(['id', 'nip']);
      Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['sendmail_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      // $peserta = Peserta::select('email')->whereIn('nip', $pendaftaran->pluck('nip')->all())->whereNotNull('email')->get();
      // $peserta = Peserta::select('email')->whereIn('email', ['devdanil14@gmail.com', 'danilstmik14@gmail.com'])->get();
      // if ($peserta) {
      //   // Notification::send($peserta, new ConfirmationPeserta($event->pelatihan));
      //   Mail::to('danilpitopang02@gmail.com')->bcc($peserta)->send(new MailConfirmationPeserta($event->pelatihan));
      //   Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['sendmail_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      // }
    } else if ($event->pelatihan->status_id == 6) {
      $pendaftaran = Pendaftaran::whereNotNull('confirmed_at')->whereNotNull('sendmail_at')->whereNull('rejected_at')->where('pelatihan_id', $event->pelatihan->id)->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->limit($event->pelatihan->kuota)->get(['id', 'nip']);

      Pendaftaran::whereNull('confirmed_at')->where('pelatihan_id', $event->pelatihan->id)->update(['rejected_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['approved_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      // $peserta = Peserta::select('email')->whereIn('nip', $pendaftaran->pluck('nip')->all())->whereNotNull('email')->get();
      // $peserta = Peserta::select('email')->whereIn('email', ['devdanil14@gmail.com', 'danilstmik14@gmail.com'])->get();
      // if ($peserta) {
      //   // Notification::send($peserta, new ApprovedPeserta($event->pelatihan));
      //   Mail::to('danilpitopang02@gmail.com')->bcc($peserta)->send(new ApprovePeserta($event->pelatihan));
      //   Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['approved_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      // }
    }
  }
}
