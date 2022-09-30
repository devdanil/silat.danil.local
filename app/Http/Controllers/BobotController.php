<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBobotRequest;
use App\Models\Katalog;
use App\Models\Pelatihan;
use App\Models\PelatihanBobot;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BobotController extends Controller
{

    public function store(StoreBobotRequest $request)
    {
        $data           = $request->validated();
        PelatihanBobot::updateOrCreate(['id' => $request->post('id')], $data);
        $this->generate_bobot($data['pelatihan_id']);
        $request->session()->flash('flash.msg', 'Data berhasil disimpan');
        $request->session()->flash('flash.error', false);
        return back();
    }
    public function destroy(PelatihanBobot $bobot, Request $request)
    {
        $bobot->delete();
        $this->generate_bobot($bobot->pelatihan_id);
        $request->session()->flash('flash.msg', 'Data berhasil dihapus');
        $request->session()->flash('flash.error', false);
        return back();
    }

    private function generate_bobot($pelatihan_id)
    {
        $pelatihan = Pelatihan::find($pelatihan_id);
        if ($pelatihan->bobot->count() > 0) {
            $katalog = $pelatihan->katalog;
            $jenis_pelatihan = $katalog->jenis_pelatihan;
            $peserta = Pendaftaran::select('id', 'nip')->where('pelatihan_id', $pelatihan_id)->with('peserta', function ($query) use ($jenis_pelatihan) {
                $query->select('nip', 'kd_jabatan', 'angka_kredit', 'lokasi_dinas')->withCount(['riwayatPelatihan as gagal' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->where('status', '!=', 'Disetujui');
                }, 'riwayatPelatihan as mengulang' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
                }]);
            })->get();
            // $i = 0;
            // $temp = [];
            foreach ($peserta as $key) {
                $bobot = 0;
                foreach ($pelatihan->bobot as $key2) {
                    if ($key2->key == 'less_kredit' && $key->peserta->angka_kredit < $katalog->angka_kredit) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'more_kredit' && $key->peserta->angka_kredit >= $katalog->angka_kredit) {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'instansi_uml' && $key->peserta->lokasi_dinas == 'uml') {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'instansi_pusat' && $key->peserta->lokasi_dinas == 'pusat') {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'pernah_gagal' && $key->peserta->gagal > 0) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'peserta_mengulang' && $key->peserta->mengulang > 0) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } else {
                        if ($key2->key == $key->peserta->kd_jabatan) {
                            $bobot = $bobot + $key2->bobot;
                            // $temp[$i][$key2->key] = $key2->bobot;
                        }
                    }
                }
                // $i++;
                Pendaftaran::where('id', $key->id)->update(['jumlah_bobot' => $bobot, 'updated_by' => Auth::id()]);
            }
        }
    }
}
