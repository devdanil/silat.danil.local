<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard(Request $request)
    {
        $data['title'] = "Dashboard";
        $data['count_new'] = 12;
        $data['count_progress'] = 24;
        $data['count_success'] = 36;
        $data['count_failed'] = 48;
        $data['pelatihan'] = [];
        $data['filter']['year'] = $request->get('year') ?? date("Y");
        for ($i = 1; $i < 13; $i++) {
            $data['pelatihan']['new'][] = 1;
            $data['pelatihan']['progress'][] = 2;
            $data['pelatihan']['success'][] = 3;
            $data['pelatihan']['failed'][] = 4;
        }
        $data['years'] = range(date('Y'), 1900);
        return Inertia::render('Dashboard', $data);
    }
}
