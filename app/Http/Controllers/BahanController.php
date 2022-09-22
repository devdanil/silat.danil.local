<?php

namespace App\Http\Controllers;

use App\Models\BahanPelatihan;

class BahanController extends Controller
{
    public function destroy(BahanPelatihan $bahan)
    {
        $bahans = BahanPelatihan::where('pelatihan_id', $bahan->pelatihan_id)->where('id', '!=', $bahan->id)->get();
        $bahan->delete();
        return response()->json($bahans);
    }
}
