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
        return view('pages.admin.iuran.index');
    }

    public function fetchIuran()
    {
        try {
            $iuran = IuranTransaction::with(['user'])->orderBy('id', 'desc')->limit(100)->get();
            return FormatResponseJson::success($iuran, 'iuran fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function storeIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::create($request->all());
            return FormatResponseJson::success($iuran, 'iuran created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function detailIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::with(['transactionDetail.user.dialisis'])
                ->where('id', $request->id)
                ->first();
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
            return FormatResponseJson::success($iuran, 'iuran updated successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }

    public function approveIuran(Request $request)
    {
        try {
            $iuran = IuranTransaction::findOrFail($request->master_iuran_id);
            $iuran->update(['status' => 'success']);
            return FormatResponseJson::success($iuran, 'Approve iuran successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }

    /**
     * Edit detail iuran (admin: tidak ada pengkondisian kepemilikan user).
     * Admin bisa edit detail manapun tanpa batasan status.
     * Setelah edit, grand_total di transaksi induk otomatis direcalculate.
     */
    public function editDetailIuran(Request $request)
    {
        try {
            $request->validate([
                'detail_id'                => 'required|exists:iuran_transaction_details,id',
                'tahun_iuran'              => 'required|digits:4|integer',
                'biaya_regis_anggota_baru' => 'required|numeric|min:0',
                'biaya_iuran_tahunan'      => 'required|numeric|min:0',
            ], [
                'tahun_iuran.required'              => 'Tahun iuran wajib diisi.',
                'tahun_iuran.digits'                => 'Tahun iuran harus terdiri dari 4 digit.',
                'tahun_iuran.integer'               => 'Tahun iuran harus berupa angka.',
                'biaya_regis_anggota_baru.required' => 'Biaya registrasi anggota baru wajib diisi.',
                'biaya_regis_anggota_baru.numeric'  => 'Biaya registrasi anggota baru harus berupa angka.',
                'biaya_regis_anggota_baru.min'      => 'Biaya registrasi anggota baru tidak boleh kurang dari 0.',
                'biaya_iuran_tahunan.required'      => 'Biaya iuran tahunan wajib diisi.',
                'biaya_iuran_tahunan.numeric'       => 'Biaya iuran tahunan harus berupa angka.',
                'biaya_iuran_tahunan.min'           => 'Biaya iuran tahunan tidak boleh kurang dari 0.',
            ]);

            $detail = IuranTransactionDetail::findOrFail($request->detail_id);

            $detail->update([
                'tahun_iuran'              => $request->tahun_iuran,
                'biaya_regis_anggota_baru' => (float) $request->biaya_regis_anggota_baru,
                'biaya_iuran_tahunan'      => (float) $request->biaya_iuran_tahunan,
            ]);

            // Recalculate grand_total pada transaksi induk
            $newGrandTotal = IuranTransactionDetail::where('tr_iuran_id', $detail->tr_iuran_id)
                ->selectRaw('SUM(biaya_regis_anggota_baru + biaya_iuran_tahunan) as total')
                ->value('total');

            IuranTransaction::where('id', $detail->tr_iuran_id)
                ->update(['grand_total' => (float) $newGrandTotal]);

            return FormatResponseJson::success($detail, 'Detail iuran berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Admin editDetailIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal memperbarui data. Silahkan hubungi developer.', 500);
        }
    }

    /**
     * Hapus transaksi iuran beserta seluruh detailnya (admin: tidak ada pengkondisian kepemilikan/status).
     * Admin bisa hapus transaksi manapun tanpa batasan.
     */
    public function deleteTransaksiIuran(Request $request)
    {
        try {
            $request->validate([
                'iuran_id' => 'required|exists:iuran_transactions,id',
            ]);

            $transaksi = IuranTransaction::findOrFail($request->iuran_id);

            // Hapus file bukti transfer jika ada
            $filePath = public_path($transaksi->iuran_image_transfer);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Hapus detail dulu sebelum master
            IuranTransactionDetail::where('tr_iuran_id', $transaksi->id)->delete();
            $transaksi->delete();

            return FormatResponseJson::success(null, 'Transaksi iuran berhasil dihapus');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Admin deleteTransaksiIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal menghapus transaksi. Silahkan hubungi developer.', 500);
        }
    }
}