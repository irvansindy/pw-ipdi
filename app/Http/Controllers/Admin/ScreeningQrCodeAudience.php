<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\CategoriesEvent;
use App\Helpers\FormatResponseJson;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportAttendance;
class ScreeningQrCodeAudience extends Controller
{
    public function index()
    {
        return view('pages.admin.attendance_audience.index');
    }
    public function viewListAudience(Request $request)
    {
        $categories = CategoriesEvent::all(); // Sesuaikan nama modelmu
        return view('pages.admin.attendance_audience.list', compact('categories'));
    }
    public function getDataAudienceById(Request $request)
    {
        try {
            $id = $request->input('id');

            $data = Transactions::with(['event', 'user.dialisis', 'transactionDetail'])
                ->where('number', $id)
                ->orWhere('token_register', $id)
                ->first();

            if ($data && isset($data->transactionDetail[0])) {
                $data->transactionDetail[0]->update([
                    'is_attendanced' => 1,
                ]);
            }

            // dd($data->transactionDetail[0]->is_attendanced);
            return FormatResponseJson::success($data, 'Berhasil mendapatkan data transaksi');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan data transaksi', 400);
        }
    }
    public function fetchDataIsAttendance(Request $request)
    {
        try {
            $categoryId = $request->input('category'); // ambil input kategori jika ada

            $data = Transactions::with(['event.category', 'user.dialisis', 'transactionDetail'])
                ->whereHas('transactionDetail', function ($query) {
                    $query->where('is_attendanced', 1);
                })
                ->when($categoryId, function ($query, $categoryId) {
                    $query->whereHas('event.category', function ($q) use ($categoryId) {
                        $q->where('id', $categoryId);
                    });
                })
                ->get();

            return FormatResponseJson::success($data, 'Berhasil mendapatkan status kehadiran');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan status kehadiran', 400);
        }
    }
    public function export(Request $request)
    {
        $category = $request->input('category');
        $year = $request->input('year');

        $fileName = 'attendance_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ExportAttendance($category, $year), $fileName);
    }
}
