<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vision;
use App\Models\Mision;
use App\Helpers\FormatResponseJson;

class VisionAndMisionController extends Controller
{
    public function index()
    {
        return view('pages.user.visi_misi.index');
    }
    public function visionAndMision()
    {
        try {
            $visi = Vision::all();
            $misi = Mision::all();

            $data = [
                'visi' => $visi,
                'misi'=> $misi
            ];
            return FormatResponseJson::success($data,'data fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}
