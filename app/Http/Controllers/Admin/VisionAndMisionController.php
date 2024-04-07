<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function detailVision(Request $request)
    {
        try {
            $vision = Vision::findOrFail($request->id);
            return FormatResponseJson::success($vision,'vision fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function createOrUpdateMision(Request $request) {
        try {
            if ($request->id == '' && $request->ordering == '') {
                $mision = Mision::orderBy('ordering', 'desc')->first();
                $ordering = $mision->ordering + 1;
                $request->ordering = $ordering;
            }

            $data = Mision::updateOrCreate([
                'id' => $request->id
            ], [
                'content' => $request->content,
                'ordering' => $request->ordering
            ]);
            return FormatResponseJson::success($data, 'mision created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function detailMision(Request $request)
    {
        try {
            $mision = Mision::findOrFail($request->id);
            return FormatResponseJson::success($mision,'mision fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
}
