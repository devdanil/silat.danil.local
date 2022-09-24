<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['title']          = "Dashboard";

        $data['filter']['year'] = $request->get('year') ?? date("Y");

        $pelatihan     =  Pelatihan::whereYear('created_at', $data['filter']['year'])->where('status_id', '!=', null)->get();
        $data['count_new']      =  $pelatihan->where('status_id', 1)->count();
        $data['count_progress'] =  $pelatihan->whereIn('status_id', [2, 4, 6, 8, 9, 11])->count();
        $data['count_failed']  =  $pelatihan->whereIn('status_id', [3, 5, 7, 10, 12])->count();
        $data['count_success']   =  $pelatihan->where('status_id', 13)->count();
        $data['pelatihan'] = [];
        for ($i = 1; $i < 13; $i++) {
            $pelatihan     =  Pelatihan::whereYear('created_at', $data['filter']['year'])->whereMonth('created_at', $i)->get();
            $data['pelatihan']['new'][]      =  $pelatihan->where('status_id', 1)->count();
            $data['pelatihan']['progress'][] =  $pelatihan->whereIn('status_id', [2, 4, 6, 8, 9, 11])->count();
            $data['pelatihan']['failed'][]  =  $pelatihan->whereIn('status_id', [3, 5, 7, 10, 12])->count();
            $data['pelatihan']['success'][]   =  $pelatihan->where('status_id', 13)->count();
        }

        $data['years'] = range(date('Y'), 1900);

        return Inertia::render('Dashboard', $data);
    }
}
