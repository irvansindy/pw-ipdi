<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
class HistoryController extends Controller
{
    public function index()
    {
        return view('pages.admin.history.index');
    }
    public function fetchHistory()
    {
        try {
            $history = History::all();
            return FormatResponseJson::success($history,'history fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function createOrUpdateHistory(Request $request)
    {
        try {
            // $request->validate([
            //     'content' => 'required|string',
            // ]);
            $validator = Validator::make($request->all(),[
                'content' => 'required|string',
            ]);
            $data = History::updateOrCreate([
                'id'=> $request->id,
            ], [
                'content'=> $request->content,
            ]);
            // Check if validation fails
            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            return FormatResponseJson::success($data, 'success');
        } catch (\Illuminate\Validation\ValidationException $th) {
            // return FormatResponseJson::error(null, $th->validator->errors()->all(), 422);
            return response()->json([
                'errors' => $th->validator->errors()->all()
            ], 422);
        } 
        catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function detailHistory(Request $request)
    {
        try {
            $history = History::findOrFail($request->id);
            return FormatResponseJson::success($history,'history fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}
