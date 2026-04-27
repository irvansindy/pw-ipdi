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
            $iuran = Iuran::orderBy('id', 'desc')->get();
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
                    $query->where('name', '!=', 'superadmin');
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
            // Hitung total regis (20k) dan total iuran tahunan (50k) dengan memperhitungkan jumlah tahun yang dipilih
            $iuran20 = $request->input('iuran_20k', []);
            $iuran50 = $request->input('iuran_50k', []);
            $tahun = $request->input('tahun_pembayaran_iuran', []);

            $total_iuran_20k = 0;
            $total_iuran_50k = 0;

            $max = max([count($iuran20 ?? []), count($iuran50 ?? []), count($tahun ?? [])]);
            for ($i = 0; $i < $max; $i++) {
                $reg = isset($iuran20[$i]) && is_numeric($iuran20[$i]) ? (float)$iuran20[$i] : 0;
                $val50 = isset($iuran50[$i]) && is_numeric($iuran50[$i]) ? (float)$iuran50[$i] : 0;

                $yearsCount = 0;
                if (isset($tahun[$i])) {
                    if (is_array($tahun[$i])) {
                        $yearsCount = count($tahun[$i]);
                    } elseif ($tahun[$i] !== '') {
                        $yearsCount = 1;
                    }
                }

                $total_iuran_20k += $reg;
                $total_iuran_50k += $val50 * $yearsCount;
            }

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
            $request->validate([
                'iuran_type' => 'required|in:mandiri,kolektif',
                'iuran_file_upload' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
                'peserta' => 'required|array|min:1',
                'peserta.*' => 'required|exists:users,id',
                'iuran_20k' => 'required|array',
                'iuran_50k' => 'required|array',
                'tahun_pembayaran_iuran' => 'required|array',
                'tahun_pembayaran_iuran.*' => 'required|array|min:1',
            ]);

            $lastTransaction = IuranTransaction::latest('id')->first();
            $numbering = ($lastTransaction ? $lastTransaction->id : 0) + 1;
            $result_number = 'IURAN-' . now()->format('y/m/d') . '/' . $numbering;

            $iuran_type = $request->iuran_type === 'kolektif' ? 1 : 0;

            // Hitung total dengan memperhitungkan jumlah tahun yang dipilih per peserta
            $iuran20 = $request->input('iuran_20k', []);
            $iuran50 = $request->input('iuran_50k', []);
            $tahun = $request->input('tahun_pembayaran_iuran', []);

            $total_iuran_20k = 0;
            $total_iuran_50k = 0;

            $max = max([count($iuran20 ?? []), count($iuran50 ?? []), count($request->peserta ?? [])]);
            for ($i = 0; $i < $max; $i++) {
                $reg = isset($iuran20[$i]) && is_numeric($iuran20[$i]) ? (float)$iuran20[$i] : 0;
                $val50 = isset($iuran50[$i]) && is_numeric($iuran50[$i]) ? (float)$iuran50[$i] : 0;

                $yearsCount = 0;
                if (isset($tahun[$i])) {
                    if (is_array($tahun[$i])) {
                        $yearsCount = count($tahun[$i]);
                    } elseif ($tahun[$i] !== '') {
                        $yearsCount = 1;
                    }
                }

                $total_iuran_20k += $reg;
                $total_iuran_50k += $val50 * $yearsCount;
            }
            $grand_total = (float)($total_iuran_20k + $total_iuran_50k);

            if ($grand_total <= 0) {
                return FormatResponseJson::error(null, 'Total iuran harus lebih dari 0', 400);
            }

            $file = $request->file('iuran_file_upload');
            $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $filePath = 'storage/iuran/' . $fileName;

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

            if (count($request->peserta) > 0) {
                $details = [];
                foreach ($request->peserta as $i => $peserta_id) {
                    $reg = isset($iuran20[$i]) && is_numeric($iuran20[$i]) ? (float)$iuran20[$i] : 0;
                    $val50 = isset($iuran50[$i]) && is_numeric($iuran50[$i]) ? (float)$iuran50[$i] : 0;

                    $years = $request->tahun_pembayaran_iuran[$i] ?? [];
                    $yearsCount = is_array($years) ? count($years) : ($years !== '' ? 1 : 0);
                    $biayaTahunan = $val50 * $yearsCount;

                    $details[] = [
                        'tr_iuran_id' => $master_iuran->id,
                        'user_id' => $peserta_id,
                        'tahun_iuran' => is_array($years) ? json_encode(array_values($years)) : ($years ?: now()->year),
                        'biaya_regis_anggota_baru' => $reg,
                        'biaya_iuran_tahunan' => $biayaTahunan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                IuranTransactionDetail::insert($details);

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
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in storeIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal menyimpan data. Silahkan hubungi admin.', 500);
        }
    }

    public function fetchTransaksiIuran()
    {
        try {
            $authId = Auth::id();

            $transaksi = IuranTransactionDetail::with(['user', 'iuran'])
                ->whereHas('user', function ($query) use ($authId) {
                    $query->where('id', $authId);
                })
                ->get()
                ->map(function ($detail) use ($authId) {
                    // Tambahkan flag is_owner agar front-end bisa tampilkan tombol Delete
                    // hanya kepada user yang memiliki relasi ke IuranTransaction (user_id)
                    if ($detail->iuran) {
                        $detail->iuran->is_owner = ($detail->iuran->user_id === $authId);
                    }
                    return $detail;
                });

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

            if (!is_dir(public_path('uploads/bukti_transaksi/iuran'))) {
                mkdir(public_path('uploads/bukti_transaksi/iuran'), 0755, true);
            }

            $transaction = IuranTransaction::findOrFail($request->master_iuran_id);

            $oldFile = public_path(str_replace('storage/', '', $transaction->iuran_image_transfer));
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            $file->move(public_path('uploads/bukti_transaksi/iuran'), $fileName);
            $transaction->update([
                'status' => 'verified',
                'iuran_image_transfer' => 'uploads/bukti_transaksi/iuran/' . $fileName
            ]);

            return FormatResponseJson::success($transaction, 'File berhasil diupload');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in uploadIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal melakukan upload. Silahkan hubungi admin', 500);
        }
    }

    /**
     * Edit detail iuran milik user yang sedang login.
     * Hanya bisa dilakukan jika transaksi masih berstatus 'progress'.
     * User hanya bisa mengedit row detail yang memiliki relasi dengan dirinya.
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

            // Pastikan detail ini milik user yang sedang login
            $detail = IuranTransactionDetail::with('iuran')
                ->where('id', $request->detail_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$detail) {
                return FormatResponseJson::error(null, 'Data detail iuran tidak ditemukan atau bukan milik Anda', 403);
            }

            // Pastikan transaksi induk masih berstatus 'progress' (belum diapprove)
            if ($detail->iuran->status !== 'progress') {
                return FormatResponseJson::error(null, 'Transaksi sudah diverifikasi, tidak dapat diedit', 403);
            }

            $detail->update([
                'tahun_iuran'              => $request->tahun_iuran,
                'biaya_regis_anggota_baru' => (float)$request->biaya_regis_anggota_baru,
                'biaya_iuran_tahunan'      => (float)$request->biaya_iuran_tahunan,
            ]);

            // Recalculate grand_total pada transaksi induk
            $newGrandTotal = IuranTransactionDetail::where('tr_iuran_id', $detail->tr_iuran_id)
                ->selectRaw('SUM(biaya_regis_anggota_baru + biaya_iuran_tahunan) as total')
                ->value('total');

            $detail->iuran->update(['grand_total' => (float)$newGrandTotal]);

            return FormatResponseJson::success($detail, 'Detail iuran berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in editDetailIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal memperbarui data. Silahkan hubungi admin.', 500);
        }
    }

    /**
     * Hapus transaksi iuran beserta seluruh detailnya.
     * Hanya bisa dilakukan oleh user yang memiliki relasi ke IuranTransaction (user_id).
     * Hanya bisa dihapus jika masih berstatus 'progress'.
     */
    public function deleteTransaksiIuran(Request $request)
    {
        try {
            $request->validate([
                'iuran_id' => 'required|exists:iuran_transactions,id',
            ]);

            // Pastikan transaksi ini adalah milik user yang sedang login
            $transaksi = IuranTransaction::where('id', $request->iuran_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$transaksi) {
                return FormatResponseJson::error(null, 'Transaksi tidak ditemukan atau bukan milik Anda', 403);
            }

            // Hanya boleh hapus jika belum diverifikasi
            if ($transaksi->status !== 'progress') {
                return FormatResponseJson::error(null, 'Transaksi sudah diverifikasi, tidak dapat dihapus', 403);
            }

            // Hapus file bukti transfer jika ada
            $filePath = public_path($transaksi->iuran_image_transfer);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Hapus detail dan master (detail dihapus dulu karena foreign key)
            IuranTransactionDetail::where('tr_iuran_id', $transaksi->id)->delete();
            $transaksi->delete();

            return FormatResponseJson::success(null, 'Transaksi iuran berhasil dihapus');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kirim sebagai object agar JS bisa render error per field
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            \Log::error('Error in deleteTransaksiIuran: ' . $e->getMessage());
            return FormatResponseJson::error(null, 'Gagal menghapus transaksi. Silahkan hubungi admin.', 500);
        }
    }
}
