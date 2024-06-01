<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Events;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionDetail;
use App\Models\Hotel;
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
    public function createTransaction(Request $request)
    {
        try {
            DB::beginTransaction();
            // $id  = $request->input('id');
            $id  = $request->event_id;
            $category_id = $request->category_id;
            $event_type = $request->event_type;
            $list_peserta = $request->list_peserta;
            $list_peserta != null ? $total_peserta = count($list_peserta) : $total_peserta = 0;
            $with_hotel = $request->with_hotel;
            $jnns_type = $request->jnns_type;
            $harga_hotel = $request->harga_hotel;
            $total_kamar_hotel = $request->total_kamar_hotel;
            $total_permalam = $request->total_permalam;
            $total_harga_hotel = $request->total_harga_hotel;
            $event_harga = $request->event_harga;
            // dd($total_harga_hotel);
            $total_peserta <= 0 ? $total_harga_event = $event_harga : $total_harga_event = $event_harga * $total_peserta;
            
            if ($with_hotel == 0) {
                $grand_total_harga = $total_harga_event;
            } else if ($with_hotel == 1) {
                $grand_total_harga = $total_harga_event + $total_harga_hotel;
            }
            // $jnns_type = $request->jnns_type;
            // $jnns_harga = $request->jnns_harga;
            // dd($request->all());
            // dd($grand_total_harga);
            $check_total_current_transaction = Transactions::where('event_id', $id)->count();
            $check_quota_event = Events::where('id', $id)->get();
            
            if ($check_total_current_transaction < $check_quota_event[0]->quota) {
                // generate transaction number
                $code_trans = '';
                if ($category_id == 1) {
                    if ($jnns_type == 1) {
                        $code_trans = 'JNNS-SYMP';
                    } else if ($jnns_type == 2) {
                        $code_trans = 'JNNS-WS';
                    } else if ($jnns_type == 3) {
                        $code_trans = 'JNNS-PKT';
                    }
                } else if ($category_id == 2) {
                    $code_trans = 'SMNR';
                } else if ($category_id == 3) {
                    $code_trans = 'RSTF';
                } else if ($category_id == 4) {
                    $code_trans = 'WKD';
                } else if ($category_id == 5) {
                    $code_trans = 'KOWIL';
                }

                $date_trans = now()->format('y/m/d');
                $numbering = $check_total_current_transaction + 1;
                $result_number = $code_trans.'-'.$date_trans.'/'.$numbering;
                // dd($result_number);
                // if ($category_id == 1) {
                //     $price = $event_harga;
                // } else {
                //     $price = null;
                // }

                $create_transaction = Transactions::create([
                    'number' => $result_number,
                    'user_id' => Auth::user()->id,
                    'event_id' => $id,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'price' => $grand_total_harga,
                    'with_hotel' => $with_hotel,
                    'harga_hotel' => $harga_hotel,
                    'total_room' => $total_kamar_hotel,
                    'total_night' => $total_permalam,
                    'proof_of_payment' => null
                ]);
                $data_user = [
                    'transaction_id' => $create_transaction->id,
                    'user_id' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                TransactionDetail::create($data_user);
                if ($list_peserta != null) {
                    $data_peserta = [];
                    $each_peserta = [];
                    for ($i=0; $i < count($list_peserta); $i++) { 
                        $each_peserta = [
                            'transaction_id' => $create_transaction->id,
                            'user_id' => $request->list_peserta[$i],
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        array_push($data_peserta, $each_peserta);
                    }
                    TransactionDetail::insert($data_peserta);
                }                
                DB::commit();
                return FormatResponseJson::success($create_transaction, 'Berhasil melakukan transaksi, silahkan upload bukti transfer untuk konfirmasi pendaftaran. Cek riwayat transaksi untuk upload.');
            } else {
                DB::rollBack();
                return FormatResponseJson::error(null, 'Gagal melakukan transaksi', 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function viewTransaction()
    {
        return view('pages.user.transactions.index');
    }
    public function transactionHistory()
    {
        try {
            $transactions = Transactions::with('event')
            ->where('user_id', Auth::user()->id)->get();
            return FormatResponseJson::success($transactions,'Transaction fetched successfully');
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
}
