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
use Illuminate\Support\Facades\Auth;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
                // dd($check_user_data);
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
    public function createTransaction1(Request $request)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();

            $event_id = $request->id;
            $event_price = $request->price;
            $user_id = $request->user_id;
            $detail_event_id = $request->detail_event;
            // dd($detail_event_id);
            $event_hotel_id = $request->event_hotel;
            $is_hotel_checked = $request->is_hotel_checked;
            $total_room_hotel = $request->total_room_hotel;
            $total_room_night = $request->total_room_night;
            
            $existing_event = Events::with(['category'])
            ->where('id', $event_id)->first();
            $existing_event_detail = null;
            $existing_hotel = null;
            if ($detail_event_id) {
                $existing_event_detail = EventDetail::where('id', intval($detail_event_id))->first();
            }
            $final_event_price = $existing_event_detail != null ? intval($existing_event_detail->price) : intval($event_price);
            $check_hotel = intval($is_hotel_checked);
            if ($check_hotel == 1) {
                $existing_hotel = EventHotel::where('id', $event_hotel_id)->first();
            }
            // dd($existing_hotel->price);
            $final_hotel_price = $existing_hotel != null ? intval($existing_hotel->price) : 0;
            $grand_total_hotel = $final_hotel_price * $total_room_hotel * $total_room_night;
            // dd($final_hotel_price);
            $check_total_current_transaction = Transactions::where('event_id', $event_id)->count();
            $check_quota_event = $existing_event->quota;
            
            $code_trans = '';
            if ($check_total_current_transaction < $check_quota_event) {
                switch ($existing_event->category->name) {
                    case 'JNNS':
                        if ($existing_event_detail->name == 'Simposium') {
                            $code_trans = 'JNNS-SYMP';
                        } else if ($existing_event_detail->name == 'Workshop') {
                            $code_trans = 'JNNS-WS';
                        } else if ($existing_event_detail->name == 'Paket') {
                            $code_trans = 'JNNS-PKT';
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
                    $code_trans = '';
                        break;
                }

                $date_trans = now()->format('y/m/d');
                $numbering = $check_total_current_transaction + 1;
                $result_number = $code_trans.'-'.$date_trans.'/'.$numbering;
                $grand_total_amount = $final_event_price + $grand_total_hotel;

                $create_transaction = Transactions::create([
                    'number' => $result_number,
                    'user_id' => $user_id,
                    'event_id' => $event_id,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'price' => $grand_total_amount,
                    'with_hotel' => $is_hotel_checked,
                    'harga_hotel' => $grand_total_hotel,
                    'total_room' => $total_room_hotel,
                    'total_night' => $total_room_night,
                    'proof_of_payment' => null
                ]);
                $data_user = [
                    'transaction_id' => $create_transaction->id,
                    'user_id' => $user_id,
                    'is_attendanced' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                TransactionDetail::create($data_user);
                
                DB::commit();
                return FormatResponseJson::success($create_transaction, 'Berhasil melakukan transaksi, silahkan upload bukti transfer untuk konfirmasi pendaftaran. Cek riwayat transaksi untuk upload.');
            } else {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Gagal melakukan transaksi', 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function viewTransaction()
    {
        return view('pages.user.transactions.index');
    }
    public function transactionHistory()
    {
        try {
            $list_transaction = TransactionDetail::with(['transactions.event'])->where('user_id', Auth::user()->id)->get();
            // dd($list_transaction);
            // $transactions = Transactions::with('event')->where('user_id', Auth::user()->id)->get();
            return FormatResponseJson::success($list_transaction,'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan transaksi', 400);
        }
    }
    public function detailTransaction(Request $request)
    {
        try {
            $transaction = Transactions::with(['event.eventDetail','transactionDetail.user', 'user'])
            ->where('user_id', Auth::user()->id)
            ->find($request->transaction_id);
            // ->find(4);
            return FormatResponseJson::success($transaction,'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan data transaksi', 400);
        }
    }
    public function uploadTransaction(Request $request)
    {
        try {
            $request->validate([
                'file_upload_transaction' => 'required|mimes:png,jpg,pdf|max:128'
            ]);
            
            $file = $request->file('file_upload_transaction');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_transaksi'), $file_name);
            $transaction = Transactions::find($request->id);
            $transaction->update([
                'status' => 'progress',
                'proof_of_payment' => $file_name
            ]);
            return FormatResponseJson::success($transaction,'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan upload bukti transaksi', 500);
            // return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function ticketQrCode(Request $request)
    {
        // dd($request->transnumber);
        // Membuat QR code dan menyimpan sebagai file gambar
        $qrcodePath = public_path('qrcode.png');
        // QrCode::format('png')->size(200)->generate($request->transnumber, $qrcodePath);
        QrCode::size(300)->generate($request->transnumber, $qrcodePath);

        // Load existing PDF
        $pdfPath = public_path('img/logos/bg/ticket_for_qrcode.pdf');
        // Buat instance mPDF
        $mpdf = new Mpdf();
        $mpdf->AddPage(
            'L', // L - landscape, P - portrait 
            '',
            '',
            '',
            '',
            5, // margin_left
            45, // margin right
            25, // margin top
            20, // margin bottom
            5, // margin header
            5
        );
        // Set isi PDF
        $pagecount = $mpdf->SetSourceFile($pdfPath);
        $tplId = $mpdf->ImportPage($pagecount);

        // Add the existing PDF as a template
        $mpdf->UseTemplate($tplId, 10, 10, 275);

        // Menambahkan QR code ke PDF di pojok kiri bawah
        $mpdf->Image($qrcodePath, 50, 95, 55, 55, 'png', '', true, false);
        // Add text to the PDF at the bottom right corner
        $text = Auth::user()->username;
        $number = 'number';
        $html = "
        <div style='position: absolute; bottom: 330px; right: 170px; color: white; font-size: 30px;'>
            $text
            <br>
            $request->transnumber
        </div>";
        $mpdf->WriteHTML($html);
        // Simpan PDF ke file atau kirim ke browser
        // 'I' untuk menampilkan di browser, 'D' untuk mengunduh
        return $mpdf->Output('Ticket.pdf', 'I');
    }
}
