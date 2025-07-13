<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegisterMail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
class TransactionManagementController extends Controller
{
    public function index()
    {
        return view("pages.admin.transactions.index");
    }
    public function fetch(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));

            $transactions = [
                'transaction_jnns_management' => Transactions::with(['event', 'user'])
                    ->whereYear('created_at', $year)
                    ->whereHas('event.category', fn($q) => $q->where('name', 'jnns'))
                    ->orderBy("id", "desc")->get(),

                'transaction_resertifikasi_management' => Transactions::with(['event', 'user'])
                    ->whereYear('created_at', $year)
                    ->whereHas('event.category', fn($q) => $q->where('name', 'resertifikasi'))
                    ->orderBy("id", "desc")->get(),

                'transaction_seminar_management' => Transactions::with(['event', 'user'])
                    ->whereYear('created_at', $year)
                    ->whereHas('event.category', fn($q) => $q->where('name', 'Seminar'))
                    ->orderBy("id", "desc")->get(),

                'transaction_wkd_management' => Transactions::with(['event', 'user'])
                    ->whereYear('created_at', $year)
                    ->whereHas('event.category', fn($q) => $q->where('name', 'wkd'))
                    ->orderBy("id", "desc")->get(),

                'transaction_kongres_wilayah_management' => Transactions::with(['event', 'user'])
                    ->whereYear('created_at', $year)
                    ->whereHas('event.category', fn($q) => $q->where('name', 'Kongres Wilayah'))
                    ->orderBy("id", "desc")->get(),
            ];

            return FormatResponseJson::success(['role' => auth()->user()->roles->pluck('name')[0]] + $transactions, 'Transaction fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }
    }
    public function detail(Request $request)
    {
        try {
            $transaction = Transactions::with(['transactionDetail.user', 'event.category', 'event.eventDetail', 'event.hotel', 'user'])->findOrFail($request->id);
            return FormatResponseJson::success($transaction, 'Transaction detail fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function approval(Request $request)
    {
        try {
            $transaction = Transactions::find($request->update_transaction_id);
            
            $list_transaction = TransactionDetail::with(['user'])->where('transaction_details.transaction_id', $transaction->id)->get();

            // for ($i=0; $i < count($list_transaction); $i++) { 
            //     Mail::to($list_transaction[$i]['user']->email)->send(new EventRegisterMail([
            //         'title' => $transaction->number,
            //         'body' => 'Selamat kamu sudah terdaftar dengan nomor registrasi '.$transaction->number,
            //         'footer' => 'Silahkan cek halaman riwayat transaksi sesuai nomor registrasi untuk men-download tiket acara.',
            //     ]));
            // }

            $transaction->update([
                'status' => 'success'
            ]);

            return FormatResponseJson::success($list_transaction,'Transaksi berhasil diapproved');
        } catch (\Throwable $th) {
            //throw $th;
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function generateQrCode()
    {
        $qr_code = QrCode::size(300)->generate('testing qrcode');
        return $qr_code;
    }
    public function sendEmail()
    {
        $qr_code = $this->generateQrCode();
        Mail::to('irvanmuhammad22@gmail.com')->send(new EventRegisterMail([
            'title' => 'The Title',
            'body' => 'The Body',
        ]));
    }
    public function exportExcel(Request $request)
    {
        $category = $request->input('category'); // Ambil kategori dari request
        $year = $request->input('year'); // Ambil tahun dari request

        return Excel::download(new TransactionExport($category, $year), "list_transaksi_{$category}_{$year}.xlsx");
    }
    public function listAudience(Request $request)
    {
        try {
            $list_audience = TransactionDetail::with(['user', 'transactions'])
            ->where('transaction_id', $request->transaction_id)
            ->get();
            return FormatResponseJson::success($list_audience,'Data berhasil diambil');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function updateAudience(Request $request)
    {
        try {
            $transaction = TransactionDetail::find($request->transid);
            $transaction->update([
                'is_attendanced' => 1
            ]);
            // dd($transaction);
            return FormatResponseJson::success($transaction,'Peserta sudah hadir');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function deleteAudience(Request $request)
    {
        try {
            $transaction_detail = TransactionDetail::where('transaction_id', $request->transaction_id)->first();
            if (!$transaction_detail) {
                return FormatResponseJson::error(null, 'Data tidak ditemukan', 404);
            }
            $transaction = Transactions::find($request->transaction_id);
            $transaction_detail->delete();
            $transaction->delete();
            return FormatResponseJson::success(null, 'Peserta berhasil dihapus');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
}