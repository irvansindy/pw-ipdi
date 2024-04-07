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
        return view('pages.user.history.index');
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
}
