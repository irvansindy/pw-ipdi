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
            // $transaction = Transactions::find($request->update_transaction_id);
            // dd($transaction);
            
            $list_transaction = TransactionDetail::with(['user'])->where('transaction_details.transaction_id', $transaction->id)->get();
            // dd($list_transaction['0']['user']->email);
            // generate QR Code
            // $data_qrcode = $transaction->number. ', '.$transaction->status;
            // $qrCode = QrCode::format('png')->size(200)->generate($data_qrcode);
            // $filePath = storage_path('app/public/qrcodes/qrcode.png');
            // file_put_contents($filePath, $qrCode);

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
    // public function generatePDF()
    // {
    //     $qrCode = $this->generateQrCode('testing');
    //     $pdf = PDF::loadView('pdf.template', ['qrCode' => $qrCode]);
    //     return $pdf->stream('qrcode.pdf');
    // }
    public function sendEmail()
    {
        $qr_code = $this->generateQrCode();
        Mail::to('irvanmuhammad22@gmail.com')->send(new EventRegisterMail([
            'title' => 'The Title',
            'body' => 'The Body',
        ]));
    }
}
