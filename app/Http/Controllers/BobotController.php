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
        $this->generate_bobot($bobot);
        $request->session()->flash('flash.msg', 'Data berhasil disimpan');
        $request->session()->flash('flash.error', false);
        return back();
    }
    public function destroy(PelatihanBobot $bobot, Request $request)
    {
        $bobot_ = $bobot;
        $bobot->delete();
        $this->generate_bobot($bobot_, false);
        $request->session()->flash('flash.msg', 'Data berhasil dihapus');
        $request->session()->flash('flash.error', false);
        return back();
    }

    private function generate_bobot(PelatihanBobot $pelatihan_bobot, $update = true)
    {
        $pelatihan = Pelatihan::find($pelatihan_bobot->pelatihan_id);
        if ($pelatihan->bobot->count() > 0) {
            $katalog = $pelatihan->katalog;
            $jenis_pelatihan = $katalog->jenis_pelatihan;
            $pendaftaran = Pendaftaran::select('id', 'nip')->where('pelatihan_id', $pelatihan_bobot->pelatihan_id)->with('peserta', function ($query) use ($jenis_pelatihan) {
                $query->select('nip', 'kd_jabatan', 'lokasi_dinas', 'kd_jabatan', 'kd_golongan')->withCount(['riwayatPelatihan as gagal' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->where('status', '!=', 'Disetujui');
                }, 'riwayatPelatihan as mengulang' => function ($query2) use ($jenis_pelatihan) {
                    $query2->where('jenis_pelatihan', $jenis_pelatihan)->whereYear('created_date', date('Y'));
                }])->with(['jabatan:kd_jabatan,naik_pangkat,naik_jenjang', 'golongan:golongan,last_order']);
            })->get();
            $jumlah_peserta = [];
            foreach ($pendaftaran as $key) {
                $bobot = 0;
                foreach ($pelatihan->bobot as $key2) {
                    $jumlah_peserta[$key2->key] = isset($jumlah_peserta[$key2->key]) ? $jumlah_peserta[$key2->key] : 0;
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
                    } elseif ($key2->key == 'instansi_uml' && $key->peserta->lokasi_dinas == 'uml') {
                        $bobot = $bobot + $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'instansi_pusat' && $key->peserta->lokasi_dinas == 'pusat') {
                        $bobot = $bobot + $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'pernah_gagal' && $key->peserta->gagal > 0) {
                        $bobot = $bobot - $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } elseif ($key2->key == 'peserta_mengulang' && $key->peserta->mengulang > 0) {
                        $bobot = $bobot - $key2->bobot;
                        $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                    } else {
                        if ($key2->key == $key->peserta->kd_jabatan) {
                            $bobot = $bobot + $key2->bobot;
                            $jumlah_peserta[$key2->key] = $jumlah_peserta[$key2->key] + 1;
                        }
                    }
                }
                Pendaftaran::where('id', $key->id)->update(['jumlah_bobot' => $bobot, 'updated_by' => Auth::id()]);
            }
            if ($update) {
                $pelatihan_bobot->update(['jumlah_peserta' => $jumlah_peserta[$pelatihan_bobot->key]]);
            }
        }
    }
}
