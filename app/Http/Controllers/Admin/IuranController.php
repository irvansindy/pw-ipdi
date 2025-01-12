<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\IuranTransaction;
use App\Models\IuranTransactionDetail;
class IuranController extends Controller
{
    public function index()
    {
        // dd(IuranTransactionDetail::all());
        return view('pages.admin.iuran.index');
    }
    public function fetchIuran()
    {
        try {
            $iuran = IuranTransaction::with(['user'])->orderBy("id","desc")->limit(100)->get();
            // $iuran = IuranTransaction::orderBy("id","desc")->get();
            return FormatResponseJson::success($iuran, 'iuran fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function storeIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::create($request->all());
            return FormatResponseJson::success($iuran,'iuran created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function detailIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::with(['transactionDetail.user.dialisis'])
            ->where('id',$request->id)->first();
            return FormatResponseJson::success($iuran, 'iuran fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }

    public function updateIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::findOrFail($request->id);
            $iuran->update($request->all());
            return FormatResponseJson::success($iuran,'iuran updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function approveIuran(Request $request)
    {
        try {
            // dd($request->all());
            $iuran = IuranTransaction::findOrFail($request->master_iuran_id);
            $iuran->update([
                'status' => 'success'
            ]);
            return FormatResponseJson::success($iuran,'Aprrove iuran successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}
