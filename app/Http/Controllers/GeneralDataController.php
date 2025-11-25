<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralData;

class GeneralDataController extends Controller
{
    //


    public function getData(Request $request)
    {
        $data = GeneralData::all();
        return response()->json($data);
    }

    public function editAbout(Request $request)
    {
        $request->validate([
            'about' => 'required|string|max:4000',
        ]);

        $data = GeneralData::updateOrCreate(
            [
                'key' => 'about'
            ],
            [
                'key' => 'about',
                'value' => $request->about,
            ]
        );

        return response()->json($data);
    }
}
