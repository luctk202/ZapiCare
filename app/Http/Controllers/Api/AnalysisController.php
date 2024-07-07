<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Banner\BannerRepository;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:zip|max:20480', // giới hạn 20MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $destinationPath = 'uploads/zips'; // thư mục để lưu file
            $filename = $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            return response()->json(['message' => 'File uploaded successfully', 'filename' => $filename]);
        }

    }

}
