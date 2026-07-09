<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Events;
use App\Models\EventHotel;
use App\Models\EventDetail;
use App\Models\User;
use App\Models\Bank;
use App\Models\Hotel;
use App\Models\TransactionDetail;
use App\Models\TransactionFile;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\File;
use \Milon\Barcode\DNS1D;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function checkTransaction(Request $request)
    {
        try {
            if (Auth::check()) {
                $event_id = $request->input("event_id");

                $check_transaction = Transactions::where("event_id", $event_id)
                ->where("user_id", Auth::user()->id)
                ->first();

                $check_user_data = User::where("id", Auth::user()->id)->first();

                $fetch_currect_event = Events::with(['category', 'EventDetail', 'hotel'])->find($event_id);

                $fetch_bank = Bank::all();

                $fetch_hotel = Hotel::all();

                $data = [
                    $check_transaction,
                    $fetch_currect_event,
                    $check_user_data,
                    $fetch_bank,
                    $fetch_hotel,
                ];
                return FormatResponseJson::success($data, 'Transaction fetched successfully');
            } else {
                return FormatResponseJson::error(null,'Harap login dahulu', 401);
            }
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function createTransaction(Request $request)
    {
        try {
            // Validasi biodata lengkap sebelum membuat transaksi
            if (!auth()->user()->hasCompleteBiodata()) {
                return FormatResponseJson::error(
                    ['redirect_url' => route('profile')],
                    'Biodata belum lengkap. Silakan lengkapi profil Anda terlebih dahulu.',
                    422
                );
            }

            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'id' => 'required|exists:events,id',
                'price' => 'required|numeric',
            ]);

            $event_id = $request->id;
            $event_price = $request->price;
            $user_id = auth()->id();
            $detail_event_id = $request->detail_event;
            $event_hotel_id = $request->event_hotel;
            $is_hotel_checked = $request->is_hotel_checked;
            $total_room_hotel = $request->total_room_hotel ?? 0;
            $total_room_night = $request->total_room_night ?? 0;

            // Cek apakah user sudah pernah daftar event ini
            $existing_user_transaction = Transactions::where('event_id', $event_id)
                ->where('user_id', $user_id)
                ->whereIn('status', ['pending', 'progress', 'success'])
                ->first();

            if ($existing_user_transaction) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Anda sudah terdaftar untuk event ini', 400);
            }

            // Get event data
            $existing_event = Events::with(['category'])->find($event_id);

            if (!$existing_event) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Event tidak ditemukan', 404);
            }

            $existing_event_detail = null;
            $existing_hotel = null;

            // Ambil detail event jika ada
            if ($detail_event_id) {
                $existing_event_detail = EventDetail::where('id', intval($detail_event_id))
                    ->where('event_id', $event_id)
                    ->first();

                if (!$existing_event_detail) {
                    DB::rollBack();
                    return FormatResponseJson::error(null, 'Detail event tidak ditemukan', 404);
                }
            }
            // dd($existing_event_detail->name);
            // Hitung harga event
            $final_event_price = $existing_event_detail != null
                ? intval($existing_event_detail->price)
                : intval($event_price);

            // Proses hotel jika dipilih
            $check_hotel = intval($is_hotel_checked);
            $grand_total_hotel = 0;

            if ($check_hotel == 1) {
                if (!$event_hotel_id) {
                    DB::rollBack();
                    return FormatResponseJson::error(null, 'Hotel ID tidak ditemukan', 400);
                }

                $existing_hotel = EventHotel::where('id', $event_hotel_id)
                    ->where('event_id', $event_id)
                    ->first();

                if (!$existing_hotel) {
                    DB::rollBack();
                    return FormatResponseJson::error(null, 'Hotel tidak ditemukan', 404);
                }

                if ($total_room_hotel <= 0 || $total_room_night <= 0) {
                    DB::rollBack();
                    return FormatResponseJson::error(null, 'Jumlah kamar dan malam hotel harus diisi', 400);
                }

                $final_hotel_price = intval($existing_hotel->price);
                $grand_total_hotel = $final_hotel_price * $total_room_hotel * $total_room_night;
            }

            // Cek quota event
            $check_total_current_transaction = Transactions::where('event_id', $event_id)
                ->whereIn('status', ['pending', 'progress', 'success'])
                ->count();
            $check_quota_event = $existing_event->quota;

            if ($check_total_current_transaction >= $check_quota_event) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Maaf, kuota event sudah penuh', 400);
            }
            // Generate transaction code
            $code_trans = '';
            // $category_name = $existing_event_detail->name;
            $category_name = Events::with('category')->find($event_id)->category->name;
            // dd($event_category_name);
            switch ($category_name) {
                case 'JNNS':
                    if ($existing_event_detail) {
                        if (stripos($existing_event_detail->name, 'Simposium') !== false) {
                            $code_trans = 'JNNS-SYMP';
                        } else if (stripos($existing_event_detail->name, 'Workshop') !== false) {
                            $code_trans = 'JNNS-WS';
                        } else if (stripos($existing_event_detail->name, 'Paket') !== false) {
                            $code_trans = 'JNNS-PKT';
                        } else {
                            $code_trans = 'JNNS-GEN';
                        }
                    } else {
                        $code_trans = 'JNNS-GEN';
                    }
                    break;
                case 'Seminar':
                    $code_trans = 'SMNR';
                    break;
                case 'Resertifikasi':
                    $code_trans = 'RSTF';
                    break;
                case 'WKD':
                    $code_trans = 'WKD';
                    break;
                case 'Kongres Wilayah':
                    $code_trans = 'KOWIL';
                    break;
                default:
                    $code_trans = 'EVT';
                    break;
            }
            // dd($request->all());

            $date_trans = now()->format('y/m/d');
            $numbering = $check_total_current_transaction + 1;
            $result_number = $code_trans . '-' . $date_trans . '/' . $numbering;

            // Calculate grand total
            $grand_total_amount = $final_event_price + $grand_total_hotel;

            // Create transaction dengan event_detail_id
            $create_transaction = Transactions::create([
                'number' => $result_number,
                'token_register' => Str::random(30),
                'user_id' => $user_id,
                'event_id' => $event_id,
                'event_detail_id' => $detail_event_id,
                'date' => now()->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'price' => $grand_total_amount,
                'with_hotel' => $is_hotel_checked,
                'harga_hotel' => $grand_total_hotel,
                'total_room' => $total_room_hotel,
                'total_night' => $total_room_night,
                'proof_of_payment' => null
            ]);

            // Create transaction detail
            TransactionDetail::create([
                'transaction_id' => $create_transaction->id,
                'user_id' => $user_id,
                'is_attendanced' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            // Return dengan data lengkap
            $transaction_with_detail = Transactions::with(['event', 'event.eventDetail', 'transactionDetail'])
                ->find($create_transaction->id);

            return FormatResponseJson::success(
                $transaction_with_detail,
                'Berhasil melakukan transaksi, silahkan upload bukti transfer untuk konfirmasi pendaftaran. Cek riwayat transaksi untuk upload.'
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, $e->errors(), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, 'Terjadi kesalahan: ' . $e->getMessage(), 500);
        }
    }

    public function viewTransaction()
    {
        return view('pages.user.transactions.index');
    }

    public function transactionHistory()
    {
        try {
            $list_transaction = TransactionDetail::with([
                'transactions.event.eventDetail',
                'transactions.event.category',
                'transactions.eventDetail',
                'user'
            ])
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

            return FormatResponseJson::success($list_transaction, 'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mengambil riwayat transaksi: ' . $e->getMessage(), 400);
        }
    }

    public function detailTransaction(Request $request)
    {
        try {
            $transaction = Transactions::with([
                'event.eventDetail',
                'event.category',
                'eventDetail',
                'transactionDetail.user',
                'user',
                'files'
            ])
            ->where('user_id', Auth::user()->id)
            ->find($request->transaction_id);

            if (!$transaction) {
                return FormatResponseJson::error(null, 'Transaksi tidak ditemukan', 404);
            }

            return FormatResponseJson::success($transaction, 'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan data transaksi: ' . $e->getMessage(), 400);
        }
    }

    public function uploadTransaction(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi input
            $request->validate([
                'id' => 'required|exists:transactions,id',
                'files.*' => 'required|mimes:png,jpg,jpeg,pdf|max:2048',
                'descriptions.*' => 'nullable|string|max:255'
            ], [
                'files.*.required' => 'File harus diupload',
                'files.*.mimes' => 'File harus berformat: png, jpg, jpeg, atau pdf',
                'files.*.max' => 'Ukuran file maksimal 2MB',
            ]);

            $transaction_id = $request->id;

            // Cek apakah transaksi milik user yang login
            $transaction = Transactions::where('id', $transaction_id)
                ->where('user_id', Auth::user()->id)
                ->first();

            if (!$transaction) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Transaksi tidak ditemukan', 404);
            }

            // Cek status transaksi
            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Hanya transaksi dengan status pending yang dapat diupload bukti pembayaran', 400);
            }

            $uploadedFiles = [];
            $files = $request->file('files');
            $descriptions = $request->input('descriptions', []);

            // Loop untuk upload multiple files
            foreach ($files as $index => $file) {
                // ===== PENTING: Ambil info SEBELUM move file =====
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileSize = round($file->getSize() / 1024, 2); // KB - ambil sebelum move
                $mimeType = $file->getClientMimeType(); // ambil sebelum move

                // Generate unique filename
                $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $extension;

                // Move file to public directory
                $file->move(public_path('uploads/bukti_transaksi'), $fileName);

                // Get description
                $description = $descriptions[$index] ?? null;

                // Save to database
                $transactionFile = TransactionFile::create([
                    'transaction_id' => $transaction_id,
                    'file_name' => $originalName,
                    'file_path' => $fileName,
                    'description' => $description,
                    'file_type' => 'payment_proof',
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType
                ]);

                $uploadedFiles[] = $transactionFile;
            }

            // Update transaction status dan simpan file pertama sebagai proof_of_payment utama
            $transaction->update([
                'status' => 'progress',
                'proof_of_payment' => $uploadedFiles[0]->file_path ?? null
            ]);

            DB::commit();

            return FormatResponseJson::success([
                'transaction' => $transaction,
                'uploaded_files' => $uploadedFiles
            ], 'Berhasil mengupload ' . count($uploadedFiles) . ' file');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return FormatResponseJson::error($e->errors(), 'Validasi gagal', 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, 'Gagal melakukan upload bukti transaksi: ' . $e->getMessage(), 500);
        }
    }

    public function deleteTransactionFile(Request $request)
    {
        try {
            DB::beginTransaction();

            $file = TransactionFile::where('id', $request->file_id)
                ->whereHas('transaction', function($q) {
                    $q->where('user_id', Auth::user()->id);
                })
                ->first();

            if (!$file) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'File tidak ditemukan', 404);
            }

            // Hapus file fisik
            $filePath = public_path('uploads/bukti_transaksi/' . $file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Hapus dari database
            $file->delete();

            DB::commit();
            return FormatResponseJson::success(null, 'File berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, 'Gagal menghapus file: ' . $e->getMessage(), 500);
        }
    }

    public function deleteTransaction(Request $request)
    {
        try {
            DB::beginTransaction();

            $transaction = Transactions::with('transactionDetail')
                ->where('user_id', Auth::user()->id)
                ->find($request->transaction_id);

            if (!$transaction) {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Transaksi tidak ditemukan', 404);
            }

            if ($transaction->status !== 'pending') {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Hanya transaksi dengan status pending yang dapat dihapus', 400);
            }

            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            $transaction->delete();

            DB::commit();
            return FormatResponseJson::success(null, 'Transaksi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, 'Gagal menghapus transaksi: ' . $e->getMessage(), 500);
        }
    }

    public function generateTicketQrCode(Request $request)
    {
        $existing_transaction = Transactions::where('token_register', $request->transnumber)->first();
        // dd($existing_transaction);
        $qrcodePath = public_path('qrcode.png');
        $qrcodeImage = QrCode::format('png')->size(300)->generate($request->transnumber);
        file_put_contents($qrcodePath, $qrcodeImage);

        $mpdf = new Mpdf([
            'orientation' => 'L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        // $pdfPath = public_path('img/logos/bg/bg_jnns_vxii_qrcode.pdf');
        $pdfPath = public_path('img/logos/bg/QR_Code_Registration_JNNS_XVIII.pdf');
        $pagecount = $mpdf->SetSourceFile($pdfPath);
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId, 5, 0, null, null, true);

        $qrWidth = 90;
        $qrHeight = 90;
        $qrX = ($mpdf->w - $qrWidth) / 2;
        $qrY = 160;

        $mpdf->SetFillColor(255, 255, 255);
        $mpdf->Rect($qrX - 1, $qrY - 1, $qrWidth + 2, $qrHeight + 2, 'F');
        $mpdf->Image($qrcodePath, $qrX, $qrY, $qrWidth, $qrHeight, 'png', '', true, false);

        $textX = 65;
        $textY = 165;

        $htmlTopLeft = "
        <div style='position: absolute; top: {$textY}mm; left: {$textX}mm; width: 90mm; font-size: 22pt; text-align: center; line-height: 1.1; color: #000; font-weight: bold;'>
            {$existing_transaction->transactionDetail[0]->user->username}
            <br/>
            {$existing_transaction->number}
        </div>";

        $mpdf->WriteHTML($htmlTopLeft);
        $mpdf->Output('Ticket.pdf', 'I');

        if (file_exists($qrcodePath)) {
            unlink($qrcodePath);
        }
    }
}
