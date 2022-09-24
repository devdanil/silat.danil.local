<?php

namespace App\Listeners;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;

class StorePendaftaran
{

    public function handle($event)
    {
        if ($event->pelatihan->status_id == 8) {
            $peserta =  Peserta::select('nip', 'kd_jabatan')->whereIn('kd_jabatan', $event->pelatihan->bobot->pluck('key')->all())->with('riwayatPelatihan', function ($query) {
                $query->where('jenis_pelatihan', 'fungsional');
            })->with('jabatan')->orderBy('nama_lengkap', 'ASC')->get();

            $tidak_pelatihan = $event->pelatihan->bobot()->where('key', 'tidak_pelatihan')->value('bobot');
            $ikut_pelatihan = $event->pelatihan->bobot()->where('key', 'ikut_pelatihan')->value('bobot');
            $data = [];
            Pendaftaran::where('pelatihan_id', $event->pelatihan->id)->delete();
            foreach ($peserta as $key) {
                $bobot = $event->pelatihan->bobot()->where('key', $key->kd_jabatan)->value('bobot');
                $bobot = $bobot + ($key->riwayatPelatihan()->count() > 0 ? $ikut_pelatihan : $tidak_pelatihan);
                $data[] = [
                    'nip' => $key->nip,
                    'pelatihan_id' => $event->pelatihan->id,
                    'jumlah_bobot' => $bobot,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
            }
            Pendaftaran::insert($data);
        } elseif ($event->pelatihan->status_id == 13) {
            $pendaftaran = Pendaftaran::where('confirmed_at', '!=', null)->where('rejected_at', null)->orderByDesc('jumlah_bobot')->orderBy('confirmed_at', 'asc')->limit($event->pelatihan->kuota)->get('id');
            Pendaftaran::whereIn('id', $pendaftaran->pluck('id')->all())->update(['approved_at' => date('Y-m-d H:i:s'), 'updated_by' => Auth::id()]);
        }
    }
}
