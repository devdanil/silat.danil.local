<?php

namespace App\Http\Controllers;

use App\Events\ProcessPelatihan;
use App\Http\Requests\StoreBobotRequest;
use App\Models\BobotPelatihan;
use App\Models\Pelatihan;
use App\Models\Status;

class BobotController extends Controller
{

    public function store(StoreBobotRequest $request, Pelatihan $pelatihan)
    {
        $data           = $request->safe()->except('status_id');
        $status_id      = $request->post('status_id');
        $status         = Status::find($status_id);
        BobotPelatihan::where('pelatihan_id', $pelatihan->id)->delete();

        foreach ($data  as $key => $value) {
            $temp = ['pelatihan_id' => $pelatihan->id, 'key' => $key, 'bobot' => $value];
            BobotPelatihan::create($temp);
        }

        event(new ProcessPelatihan($pelatihan,  $status_id, null));

        $request->session()->flash('flash.msg', $status_id == 2 ? "Data berhasil disimpan!" : $status->flash);
        $request->session()->flash('flash.error', false);

        return back();
    }
}
