<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\User;
use App\Models\IuranTransaction;
use App\Models\IuranTransactionDetail;
use Illuminate\Support\Str;
class IuranController extends Controller
{
    public function index()
    {
        return view('pages.user.iuran.index');
    }
    public function viewCreateIuran()
    {
        return view('pages.user.iuran.create_iuran_anggota');
    }
    public function fetchIuran()
    {
        try {
            $iuran = Iuran::orderBy('id','desc')->get();
            return FormatResponseJson::success($iuran, 'iuran fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function peserta()
    {
        try {
            $peserta = User::with(['roles', 'dialisis'])
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })
                ->get();

            return FormatResponseJson::success($peserta, 'Data peserta berhasil diambil');
        } catch (\Exception $e) {
            \Log::error('Error in peserta: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal mengambil data peserta', 500);
        }
    }
    public function generatePaymentIuran(Request $request)
    {
        try {
            $total_iuran_20k = collect($request->iuran_20k)->sum();
            $total_iuran_50k = collect($request->iuran_50k)->sum();
            $grand_total = (float)($total_iuran_20k + $total_iuran_50k);
            return FormatResponseJson::success($grand_total, 'generate user payment iuran successfully');
        } catch (\Exception $e) {
            \Log::error('Error in generatePaymentIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal menghitung total pembayaran', 500);
        }
    }
    public function storeIuran(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'iuran_type' => 'required|in:mandiri,kolektif',
                'iuran_file_upload' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
                'peserta' => 'required|array|min:1',
                'peserta.*' => 'required|exists:users,id',
                'iuran_20k' => 'required|array',
                'iuran_50k' => 'required|array',
            ]);

            // Generate transaction number using latest ID (more efficient than count)
            $lastTransaction = IuranTransaction::latest('id')->first();
            $numbering = ($lastTransaction ? $lastTransaction->id : 0) + 1;
            $result_number = 'IURAN-' . now()->format('y/m/d') . '/' . $numbering;

            // Convert iuran type to boolean (0 = mandiri, 1 = kolektif)
            $iuran_type = $request->iuran_type === 'kolektif' ? 1 : 0;

            // Calculate totals with type casting
            $total_iuran_20k = array_sum(array_filter($request->iuran_20k ?? [], 'is_numeric'));
            $total_iuran_50k = array_sum(array_filter($request->iuran_50k ?? [], 'is_numeric'));
            $grand_total = (float)($total_iuran_20k + $total_iuran_50k);

            if ($grand_total <= 0) {
                return FormatResponseJson::error(null, 'Total iuran harus lebih dari 0', 400);
            }

            // Handle file upload
            $file = $request->file('iuran_file_upload');
            $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $filePath = 'storage/iuran/' . $fileName;

            // Create master iuran transaction
            $master_iuran = IuranTransaction::create([
                'iuran_number' => $result_number,
                'iuran_id' => $iuran_type,
                'user_id' => Auth::user()->id,
                'grand_total' => $grand_total,
                'iuran_image_transfer' => $filePath,
                'date' => now(),
                'for_date' => now()->year,
                'status' => 'progress'
            ]);

            // Create transaction details in bulk (1 query instead of N)
            if (count($request->peserta) > 0) {
                $details = [];
                foreach ($request->peserta as $i => $peserta_id) {
                    $details[] = [
                        'tr_iuran_id' => $master_iuran->id,
                        'user_id' => $peserta_id,
                        'tahun_iuran' => $request->tahun_pembayaran_iuran[$i] ?? now()->year,
                        'biaya_regis_anggota_baru' => (float)($request->iuran_20k[$i] ?? 0),
                        'biaya_iuran_tahunan' => (float)($request->iuran_50k[$i] ?? 0),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Insert all details at once
                IuranTransactionDetail::insert($details);

                // Move file after successful creation
                if (!is_dir(public_path('storage/iuran'))) {
                    mkdir(public_path('storage/iuran'), 0755, true);
                }
                $file->move(public_path('storage/iuran'), $fileName);

                return FormatResponseJson::success($master_iuran, 'Iuran berhasil dibuat');
            } else {
                $master_iuran->delete();
                return FormatResponseJson::error(null, 'Silahkan isi data peserta', 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal menyimpan data. Silahkan hubungi admin.', 500);
        }
    }
    public function fetchTransaksiIuran()
    {
        try {
            $transaksi = IuranTransactionDetail::with(['user', 'iuran'])
                ->whereHas('user', function($query) {
                    $query->where('id', Auth::user()->id);
                })
                // ->select('id', 'tr_iuran_id', 'user_id', 'tahun_iuran', 'biaya_regis_anggota_baru', 'biaya_iuran_tahunan', 'created_at')
                ->get();

            return FormatResponseJson::success($transaksi, 'Data transaksi berhasil diambil');
        } catch (\Exception $e) {
            \Log::error('Error in fetchTransaksiIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal mengambil data transaksi', 500);
        }
    }
    public function detailTransaksiIuran(Request $request)
    {
        try {
            $request->validate([
                'iuran_id' => 'required|exists:iuran_transactions,id'
            ]);

            $transaksi = IuranTransaction::with(['user', 'transactionDetail.user'])
                ->where('id', $request->iuran_id)
                ->first();

            if (!$transaksi) {
                return FormatResponseJson::error(null, 'Data transaksi tidak ditemukan', 404);
            }

            return FormatResponseJson::success($transaksi, 'Data transaksi berhasil diambil');
        } catch (\Exception $e) {
            \Log::error('Error in detailTransaksiIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal mengambil detail transaksi', 500);
        }
    }
    public function uploadIuran(Request $request)
    {
        try {
            $request->validate([
                'file_iuran_data' => 'required|mimes:png,jpg,jpeg,pdf|max:5120',
                'master_iuran_id' => 'required|exists:iuran_transactions,id'
            ]);

            $file = $request->file('file_iuran_data');
            $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

            // Ensure directory exists
            if (!is_dir(public_path('uploads/bukti_transaksi/iuran'))) {
                mkdir(public_path('uploads/bukti_transaksi/iuran'), 0755, true);
            }

            $transaction = IuranTransaction::findOrFail($request->master_iuran_id);

            // Delete old file if exists
            $oldFile = public_path(str_replace('storage/', '', $transaction->iuran_image_transfer));
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            // Update and move file
            $file->move(public_path('uploads/bukti_transaksi/iuran'), $fileName);
            $transaction->update([
                'status' => 'verified',
                'iuran_image_transfer' => 'uploads/bukti_transaksi/iuran/' . $fileName
            ]);

            return FormatResponseJson::success($transaction, 'File berhasil diupload');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in uploadIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal melakukan upload. Silahkan hubungi admin', 500);
        }
    }
}
