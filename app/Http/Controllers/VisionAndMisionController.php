<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vision;
use App\Models\Mision;
use App\Helpers\FormatResponseJson;

class VisionAndMisionController extends Controller
{
    public function viewVisionMision()
    {
        return view('pages.admin.visi_misi.index');
    }
    public function fetchVision()
    {
        try {
            $vision = Vision::all();
            return FormatResponseJson::success($vision, 'vision fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchMision()
    {
        try {
            $mision = Mision::orderBy('ordering','asc')->get();
            return FormatResponseJson::success($mision, 'mision fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function createOrUpdateVision(Request $request) {
        try {
            $vision = Vision::updateOrCreate([
                'id' => $request->id
            ], [
                'content' => $request->content
            ]);
            return FormatResponseJson::success($vision, 'vision updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
}
