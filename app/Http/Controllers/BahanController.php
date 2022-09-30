<?php

namespace App\Http\Controllers;

use App\Models\KatalogBahan;

class BahanController extends Controller
{
    public function destroy(KatalogBahan $bahan)
    {
        $bahans = KatalogBahan::where('katalog_id', $bahan->katalog_id)->where('id', '!=', $bahan->id)->get();
        $bahan->delete();
        return response()->json($bahans);
    }
}
