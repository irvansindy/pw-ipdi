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
        // phpinfo();
        return view("pages.admin.transactions.index");
    }
    public function fetch()
    {
        try {
            // $transactions = Transactions::with(['event', 'user'])->orderBy("id","desc")->paginate(100);
            $transactions = Transactions::with(['event', 'user'])->orderBy("id","desc")->limit(100)->get();
            return FormatResponseJson::success($transactions, 'Transaction fetched successfully');
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
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }
    }
    public function approval(Request $request)
    {
        try {
            $transaction = Transactions::find($request->update_transaction_id);
            
            $list_transaction = TransactionDetail::with(['user'])->where('transaction_details.transaction_id', $transaction->id)->get();

            for ($i=0; $i < count($list_transaction); $i++) { 
                Mail::to($list_transaction[$i]['user']->email)->send(new EventRegisterMail([
                    'title' => $transaction->number,
                    'body' => 'Selamat kamu sudah terdaftar dengan nomor registrasi '.$transaction->number,
                    'footer' => 'Silahkan cek halaman riwayat transaksi sesuai nomor registrasi untuk men-download tiket acara.',
                ]));
            }

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
    public function deleteTransaction(Request $request)
    {
        try {
            DB::beginTransaction();
            $transaction = Transactions::find($request->transaction_id);   
            TransactionDetail::where('transaction_id', $request->transaction_id)->get()->each->delete();;
            // dd($transaction->id);
            if ($transaction->proof_of_payment != null) {
                if (File::exists(public_path('uploads/bukti_transaksi/'.$transaction->proof_of_payment))) {
                    // dd($delete_attachment[$i]->document);
                    File::delete(public_path('uploads/bukti_transaksi/'.$transaction->proof_of_payment));
                }
            }
            $transaction->delete();
            DB::commit();
            return FormatResponseJson::success($transaction,'Transaksi berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollback();
            return FormatResponseJson::error(null, $th->getMessage(), 500);
        }
    }
    public function exportExcel()
    {
        return Excel::download(new TransactionExport, 'list_transaksi.xlsx');
    }
}
