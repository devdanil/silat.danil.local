<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBobotRequest;
use App\Models\AngkaKredit;
use App\Models\Katalog;
use App\Models\Pelatihan;
use App\Models\PelatihanBobot;
use App\Models\Pendaftaran;
use App\Models\Penilaian;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BobotController extends Controller
{

    public function store(StoreBobotRequest $request)
    {
        $data           = $request->validated();
        $bobot = PelatihanBobot::updateOrCreate(['id' => $request->post('id')], $data);
        $this->generate($bobot->pelatihan, $bobot);
        $request->session()->flash('flash.msg', 'Data berhasil disimpan');
        $request->session()->flash('flash.error', false);
        return back();
    }
    public function refresh(Pelatihan $pelatihan, Request $request)
    {
        $this->generate($pelatihan);
        $request->session()->flash('flash.msg', 'Data pembobotahn berhasil di referesh');
        $request->session()->flash('flash.error', false);
        return back();
    }
    public function destroy(PelatihanBobot $bobot, Request $request)
    {
        $bobot->delete();
        $this->generate($bobot->pelatihan);
        $request->session()->flash('flash.msg', 'Data berhasil dihapus');
        $request->session()->flash('flash.error', false);
        return back();
    }

    // pembobotan baru
    private function generate($pelatihan, $pelatihan_bobot = null)
    {
        if ($pelatihan->bobot->count() == 0) {
            Pendaftaran::where('pelatihan_id', $pelatihan->id)->update(['jumlah_bobot' => 0, 'updated_by' => Auth::id()]);
        } else {
            $katalog            = $pelatihan->katalog;
            $jenis_pelatihan    = $katalog->jenis_pelatihan;
            $pelatihan_id       = $pelatihan->id;
            $jumlah_peserta     = [];
            $pendaftaran        = Pendaftaran::select('id', 'nip')->where('pelatihan_id', $pelatihan->id)->with('peserta', function ($query) use ($jenis_pelatihan, $pelatihan_id) {
                $query->select('nip', 'kd_jabatan', 'lokasi_dinas', 'kd_jabatan', 'kd_golongan', 'id_kota')->withCount(['riwayatPelatihan as gagal' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->where('status', 'like', 'Tidak Disetujui%');
                }, 'riwayatPelatihan as mengulang' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
                }, 'pendaftaran as mengulang2' => function ($query2)  use ($jenis_pelatihan, $pelatihan_id) {
                    $query2->whereNotNull('approved_at')->where('pelatihan_id', '!=', $pelatihan_id)->whereHas('pelatihan', function ($query3) use ($jenis_pelatihan) {
                        $query3->whereHas('katalog', function ($query4) use ($jenis_pelatihan) {
                            $query4->where('jenis_pelatihan', $jenis_pelatihan);
                        });
                    });
                }])->with(['jabatan:kd_jabatan,naik_pangkat,naik_jenjang', 'golongan:golongan,last_order']);
            })->get();

            foreach ($pendaftaran as $key) {
                $bobot = 0;
                foreach ($pelatihan->bobot as $key2) {
                    if ($key2->key == 'kabkota') {
                        $jumlah_peserta[$key2->key . $key2->kabkota_id] = isset($jumlah_peserta[$key2->key . $key2->kabkota_id]) ? $jumlah_peserta[$key2->key . $key2->kabkota_id] : 0;
                    } else {
                        $jumlah_peserta[$key2->key] = isset($jumlah_peserta[$key2->key]) ? $jumlah_peserta[$key2->key] : 0;
                    }
                    if ($key2->key == 'angka_kredit' && $key2->kd_jabatan == $key->peserta->kd_jabatan) {
                        $maximal = !$key->peserta->kd_golongan ? 15 : ($key->peserta->golongan->last_order ? $key->peserta->jabatan->naik_jenjang : $key->peserta->jabatan->naik_pangkat);
                        $minimal = $maximal * $key2->nilai / 100;
                        $kredit = AngkaKredit::where(['nip' => $key->nip, 'status' => 'Disetujui'])->orderByDesc('tgl_selesai_penilaian')->value('total_unsur_utama');
                        if ($kredit >= $minimal) {
                            $multiplier = 100 / $maximal;
                            $bobot = $bobot + (floatval(str_replace(',', '.', $kredit)) * $multiplier * $key2->bobot / 100);
                            $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                        }
                    } elseif ($key2->key == 'prestasi' && $key2->kd_jabatan == $key->peserta->kd_jabatan) {
                        $maximal = 120;
                        $minimal = $maximal * $key2->nilai / 100;
                        $skp = Penilaian::where(['nip' => $key->nip, 'status' => 'Disetujui'])->orderByDesc('tahun')->orderByDesc('id_penilaian')->value('nilai_skp_new');
                        // var_dump($key->nip . " : " . $skp . " : " . $minimal);
                        if ($skp >= $minimal) {
                            $skp = $skp > 120 ? 120 : $skp;
                            $multiplier = 100 / $maximal;
                            $bobot = $bobot + (floatval(str_replace(',', '.', $skp)) * $multiplier * $key2->bobot / 100);
                            $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                        }
                    } elseif ($key2->key == 'instansi_uml' && $key->peserta->lokasi_dinas != 'pusat') {
                        $bobot = $bobot + $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'instansi_pusat' && $key->peserta->lokasi_dinas == 'pusat') {
                        $bobot = $bobot + $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'pernah_gagal' && $key->peserta->gagal > 0) {
                        $bobot = $bobot - $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'peserta_mengulang' && ($key->peserta->mengulang > 0 || $key->peserta->mengulang2 > 0)) {
                        $bobot = $bobot - $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'kabkota' && $key2->kabkota_id == $key->peserta->id_kota) {
                        $bobot = $bobot + $key2->bobot;
                        $jumlah_peserta[$key2->key . $key2->kabkota_id] = $jumlah_peserta[$key2->key . $key2->kabkota_id] + 1;
                    } else {
                        if ($key2->key == $key->peserta->kd_jabatan) {
                            $bobot = $bobot + $key2->bobot;
                            $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                        }
                    }
                }
                Pendaftaran::where('id', $key->id)->update(['jumlah_bobot' => $bobot, 'updated_by' => Auth::id()]);
            }

            $data_update = $pelatihan_bobot ? [$pelatihan_bobot] : $pelatihan->bobot;

            foreach ($data_update as $key) {
                if ($key->key != 'kabkota') {
                    $jumlah_peserta[$key->key] = isset($jumlah_peserta[$key->key]) ? $jumlah_peserta[$key->key] : 0;
                } else {
                    $jumlah_peserta[$key->key] = isset($jumlah_peserta[$key->key . $key->kabkota_id]) ? $jumlah_peserta[$key->key . $key->kabkota_id] : 0;
                }
                PelatihanBobot::where('id', $key->id)->update(['jumlah_peserta' => $jumlah_peserta[$key->key]]);
            }
        }
    }
    // pembobotan lama
    // private function generate_bobot(PelatihanBobot $pelatihan_bobot)
    // {
    //     $pelatihan = Pelatihan::find($pelatihan_bobot->pelatihan_id);
    //     if ($pelatihan->bobot->count() > 0) {
    //         $katalog = $pelatihan->katalog;
    //         $jenis_pelatihan = $katalog->jenis_pelatihan;
    //         $pelatihan_id = $pelatihan->id;
    //         $pendaftaran = Pendaftaran::select('id', 'nip')->where('pelatihan_id', $pelatihan_bobot->pelatihan_id)->with('peserta', function ($query) use ($jenis_pelatihan, $pelatihan_id) {
    //             $query->select('nip', 'kd_jabatan', 'lokasi_dinas', 'kd_jabatan', 'kd_golongan', 'id_kota')->withCount(['riwayatPelatihan as gagal' => function ($query2) use ($jenis_pelatihan) {
    //                 $query2->where('jenis_pelatihan', $jenis_pelatihan)->where('status', 'like', 'Tidak Disetujui%');
    //             }, 'riwayatPelatihan as mengulang' => function ($query2) use ($jenis_pelatihan) {
    //                 $query2->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
    //             }, 'pendaftaran as mengulang2' => function ($query2)  use ($jenis_pelatihan, $pelatihan_id) {
    //                 $query2->whereNotNull('approved_at')->where('pelatihan_id', '!=', $pelatihan_id)->whereHas('pelatihan', function ($query3) use ($jenis_pelatihan) {
    //                     $query3->whereHas('katalog', function ($query4) use ($jenis_pelatihan) {
    //                         $query4->where('jenis_pelatihan', $jenis_pelatihan);
    //                     });
    //                 });
    //             }])->with(['jabatan:kd_jabatan,naik_pangkat,naik_jenjang', 'golongan:golongan,last_order']);
    //         })->get();
    //         $jumlah_peserta = [];
    //         foreach ($pendaftaran as $key) {
    //             $bobot = 0;
    //             foreach ($pelatihan->bobot as $key2) {
    //                 if ($key2->key == 'kabkota') {
    //                     $jumlah_peserta[$key2->key . $key2->kabkota_id] = isset($jumlah_peserta[$key2->key . $key2->kabkota_id]) ? $jumlah_peserta[$key2->key . $key2->kabkota_id] : 0;
    //                 } else {
    //                     $jumlah_peserta[$key2->key] = isset($jumlah_peserta[$key2->key]) ? $jumlah_peserta[$key2->key] : 0;
    //                 }
    //                 if ($key2->key == 'angka_kredit' && $key2->kd_jabatan == $key->peserta->kd_jabatan) {
    //                     $maximal = !$key->peserta->kd_golongan ? 15 : ($key->peserta->golongan->last_order ? $key->peserta->jabatan->naik_jenjang : $key->peserta->jabatan->naik_pangkat);
    //                     $minimal = $maximal * $key2->nilai / 100;
    //                     $kredit = AngkaKredit::where(['nip' => $key->nip, 'status' => 'Disetujui'])->orderByDesc('tgl_selesai_penilaian')->value('total_unsur_utama');
    //                     if ($kredit >= $minimal) {
    //                         $multiplier = 100 / $maximal;
    //                         $bobot = $bobot + (floatval(str_replace(',', '.', $kredit)) * $multiplier * $key2->bobot / 100);
    //                         $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                     }
    //                 } elseif ($key2->key == 'prestasi' && $key2->kd_jabatan == $key->peserta->kd_jabatan) {
    //                     $maximal = 120;
    //                     $minimal = $maximal * $key2->nilai / 100;
    //                     $skp = Penilaian::where(['nip' => $key->nip, 'status' => 'Disetujui'])->orderByDesc('tahun')->orderByDesc('id_penilaian')->value('nilai_skp_new');
    //                     // var_dump($key->nip . " : " . $skp . " : " . $minimal);
    //                     if ($skp >= $minimal) {
    //                         $skp = $skp > 120 ? 120 : $skp;
    //                         $multiplier = 100 / $maximal;
    //                         $bobot = $bobot + (floatval(str_replace(',', '.', $skp)) * $multiplier * $key2->bobot / 100);
    //                         $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                     }
    //                 } elseif ($key2->key == 'instansi_uml' && $key->peserta->lokasi_dinas == 'uml') {
    //                     $bobot = $bobot + $key2->bobot;
    //                     $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                 } elseif ($key2->key == 'instansi_pusat' && $key->peserta->lokasi_dinas == 'pusat') {
    //                     $bobot = $bobot + $key2->bobot;
    //                     $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                 } elseif ($key2->key == 'pernah_gagal' && $key->peserta->gagal > 0) {
    //                     $bobot = $bobot - $key2->bobot;
    //                     $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                 } elseif ($key2->key == 'peserta_mengulang' && ($key->peserta->mengulang > 0 || $key->peserta->mengulang2 > 0)) {
    //                     $bobot = $bobot - $key2->bobot;
    //                     $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                 } elseif ($key2->key == 'kabkota' && $key2->kabkota_id == $key->peserta->id_kota) {
    //                     $bobot = $bobot + $key2->bobot;
    //                     $jumlah_peserta[$key2->key . $key2->kabkota_id] = $jumlah_peserta[$key2->key . $key2->kabkota_id] + 1;
    //                 } else {
    //                     if ($key2->key == $key->peserta->kd_jabatan) {
    //                         $bobot = $bobot + $key2->bobot;
    //                         $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
    //                     }
    //                 }
    //             }
    //             Pendaftaran::where('id', $key->id)->update(['jumlah_bobot' => $bobot, 'updated_by' => Auth::id()]);
    //         }
    //         if ($pelatihan_bobot->key != 'kabkota') {
    //             $jumlah_peserta[$pelatihan_bobot->key] = isset($jumlah_peserta[$pelatihan_bobot->key]) ? $jumlah_peserta[$pelatihan_bobot->key] : 0;
    //         } else {
    //             $jumlah_peserta[$pelatihan_bobot->key] = isset($jumlah_peserta[$pelatihan_bobot->key . $pelatihan_bobot->kabkota_id]) ? $jumlah_peserta[$pelatihan_bobot->key . $pelatihan_bobot->kabkota_id] : 0;
    //         }
    //         $pelatihan_bobot->update(['jumlah_peserta' => $jumlah_peserta[$pelatihan_bobot->key]]);
    //     }
    // }
}
