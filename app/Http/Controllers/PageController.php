<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['title']              = "Dashboard";

        $data['filter']['year']     = $request->get('year') ?? date("Y");

        $pelatihan                  =  Pelatihan::whereYear('created_at', $data['filter']['year'])->where('status_id', '!=', null)->get();
        $data['count']['new']       =  $pelatihan->where('status_id', 1)->count();
        $data['count']['progress']  =  $pelatihan->whereIn('status_id', [2, 3, 4, 5])->count();
        $data['count']['canceled']    =  $pelatihan->where('status_id', 8)->count();
        $data['count']['running']   =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereDate('mulai_pelatihan', '<=', date("Y-m-d"))->whereDate('selesai_pelatihan', '>=', date("Y-m-d"))->count();
        $data['count']['finish']   =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereDate('selesai_pelatihan', '<', date("Y-m-d"))->count();

        $data['pelatihan'] = [];
        for ($i = 1; $i < 13; $i++) {
            $pelatihan     =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereMonth('created_at', $i)->get();
            $data['pelatihan']['new'][]       =  $pelatihan->where('status_id', 1)->count();
            $data['pelatihan']['progress'][]  =  $pelatihan->whereIn('status_id', [2, 3, 4, 5])->count();
            $data['pelatihan']['canceled'][]    =  $pelatihan->where('status_id', 8)->count();
            $data['pelatihan']['running'][]   =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereMonth('created_at', $i)->whereDate('mulai_pelatihan', '<=', date("Y-m-d"))->whereDate('selesai_pelatihan', '>=', date("Y-m-d"))->count();
            $data['pelatihan']['finish'][]   =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereMonth('created_at', $i)->whereDate('selesai_pelatihan', '<', date("Y-m-d"))->count();
        }

        $data['years'] = range(date('Y'), 2020);

        return Inertia::render('Dashboard', $data);
    }
}
