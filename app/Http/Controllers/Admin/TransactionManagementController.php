<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Transactions;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionMail;
use App\Mail\EventRegisterMail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
class TransactionManagementController extends Controller
{
    public function index()
    {
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
            $transaction = Transactions::find($request->update_transaction_id)
            ->update([
                'status' => 'success'
            ]);
            return FormatResponseJson::success($transaction,'Transaksi berhasil diapproved');
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
