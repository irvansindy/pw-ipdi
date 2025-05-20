<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Helpers\FormatResponseJson;
class ScreeningQrCodeAudience extends Controller
{
    public function index()
    {
        return view('pages.admin.attendance_audience.index');
    }
    public function getDataAudienceById(Request $request)
    {
        try {
            $id = $request->id;
            // dd($id);
            $data = Transactions::with(['event','user.dialisis'])
            ->where('number', $id)
            ->orWhere('token_register', $id)->first();
            return FormatResponseJson::success($data, 'Berhasil mendapatkan data transaksi');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan data transaksi', 400);
        }
    }
}
