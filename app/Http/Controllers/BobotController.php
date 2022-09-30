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
        $this->generate_bobot($bobot->pelatihan_id);
        $bobot->delete();
        $request->session()->flash('flash.msg', 'Data berhasil dihapus');
        $request->session()->flash('flash.error', false);
        return back();
    }

    private function generate_bobot($pelatihan_id)
    {
        $pelatihan = Pelatihan::find($pelatihan_id);
        if ($pelatihan->bobot->count() > 0) {
            $katalog = $pelatihan->katalog;
            $katalogs = $katalog->syarat_katalog ? json_decode($katalog->syarat_katalog) : null;
            $pelatihans = $katalogs ? Pelatihan::whereIn('katalog_id', $katalogs)->get(['id']) : null;
            $instansi = $katalog->instansi;
            $ket_jabatan = json_decode($katalog->ket_jabatan);
            $jenis_pelatihan = $katalog->jenis_pelatihan;
            $peserta =  Peserta::select('nip', 'kd_jabatan', 'angka_kredit', 'lokasi_dinas')->whereIn('kd_jabatan',  $katalog->jabatan()->pluck('kd_jabatan')->all())->when($katalog->jenis_pelatihan == 'fungsional', function ($query) use ($ket_jabatan) {
                $query->whereIn('keterangan_jbt', $ket_jabatan);
            })->when($katalog->instansi != 'pusat_uml', function ($query) use ($instansi) {
                $query->where('lokasi_dinas', $instansi);
            })->when($pelatihans, function ($query) use ($pelatihans) {
                $query->whereHas('pendaftaran', function ($query2) use ($pelatihans) {
                    $query2->whereIn('pelatihan_id', $pelatihans->pluck('id')->all());
                });
            })->withCount(['riwayatPelatihan as gagal' => function ($query) use ($jenis_pelatihan) {
                $query->where('jenis_pelatihan', $jenis_pelatihan)->where('status', '!=', 'Disetujui');
            }, 'riwayatPelatihan as mengulang' => function ($query) use ($jenis_pelatihan) {
                $query->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
            }])->orderBy('nama_lengkap', 'ASC')->get();

            $data = [];
            Pendaftaran::where('pelatihan_id', $pelatihan->id)->delete();
            // $temp = [];
            $i = 0;
            foreach ($peserta as $key) {
                $bobot = 0;
                foreach ($pelatihan->bobot as $key2) {
                    if ($key2->key == 'less_kredit' && $key->angka_kredit < $katalog->angka_kredit) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'more_kredit' && $key->angka_kredit >= $katalog->angka_kredit) {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'instansi_uml' && $key->lokasi_dinas == 'uml') {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'instansi_pusat' && $key->lokasi_dinas == 'pusat') {
                        $bobot = $bobot + $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'pernah_gagal' && $key->gagal > 0) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } elseif ($key2->key == 'peserta_mengulang' && $key->mengulang > 0) {
                        $bobot = $bobot - $key2->bobot;
                        // $temp[$i][$key2->key] = $key2->bobot;
                    } else {
                        if ($key2->key == $key->kd_jabatan) {
                            $bobot = $bobot + $key2->bobot;
                            // $temp[$i][$key2->key] = $key2->bobot;
                        }
                    }
                }
                $i++;
                $data[] = [
                    'nip' => $key->nip,
                    'pelatihan_id' => $pelatihan->id,
                    'jumlah_bobot' => $bobot,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ];
            }
            Pendaftaran::insert($data);
        }
    }
}
