<?php

namespace App\Listeners;

use App\Models\Pelatihan;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\RiwayatPelatihan;
use Illuminate\Support\Facades\Auth;

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
      $peserta =  Peserta::select('nip', 'kd_jabatan')->whereIn('kd_jabatan',  $katalog->jabatan()->pluck('kd_jabatan')->all())->when($katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
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
    } else if (in_array($event->pelatihan->status_id, [4, 5])) {
      $pendaftaran = Pendaftaran::whereNotNull('registered_at')->whereNull('rejected_at')->whereNull('sendmail_at')->where('pelatihan_id', $event->pelatihan->id)->orderByDesc('jumlah_bobot')->orderBy('registered_at', 'asc')->limit($event->pelatihan->kuota)->get('id');
      Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['sendmail_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
    } else if ($event->pelatihan->status_id == 6) {
      $pendaftaran = Pendaftaran::whereNotNull('confirmed_at')->whereNotNull('sendmail_at')->whereNull('rejected_at')->where('pelatihan_id', $event->pelatihan->id)->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->limit($event->pelatihan->kuota)->get('id');
      Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['approved_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
      Pendaftaran::whereNull('confirmed_at')->where('pelatihan_id', $event->pelatihan->id)->update(['rejected_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
    }
  }
}
