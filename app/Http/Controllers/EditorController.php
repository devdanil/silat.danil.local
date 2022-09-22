<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorController extends Controller
{
    public function upload(Request $request)
    {

        $file = $request->file('file');
        if (in_array($file->extension(), ['jpg', 'png', 'jpeg']) && $file->getSize() < 2040000) {
            $file->store('public/editor/');
            $data = asset('storage/editor/' . $file->hashName());
            $result = 'success';
        } else {
            $data = 'Mohon mengupload file dengan maksimal ukuran 2MB dan berjenis: jpg, png, jpeg.';
            $result = 'failed';
        }
        return response()->json(['result' => $result, 'data' => $data]);
    }
}
