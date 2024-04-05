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

                $fetch_currect_event = Events::find($event_id);

                $fetch_bank = Bank::all();

                // dd($check_user_data);
                $data = [
                    $check_transaction,
                    $fetch_currect_event,
                    $check_user_data,
                    $fetch_bank
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
            $id  = $request->input('id');
            $check_total_current_transaction = Transactions::where('event_id', $id)->count();
            $check_quota_event = Events::where('id', $id)->get();
            
            if ($check_total_current_transaction < $check_quota_event[0]->quota) {
                // generate transaction number
                $code_trans = 'TRE';
                $date_trans = now()->format('y/m/d');
                $numbering = $check_total_current_transaction + 1;
                $result_number = $code_trans.'-'.$date_trans.'/'.$numbering;
                // dd($result_number);
                $create_transaction = Transactions::create([
                    'number' => $result_number,
                    'user_id' => Auth::user()->id,
                    'event_id' => $id,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'proof_of_payment' => null
                ]);
                return FormatResponseJson::success($create_transaction, 'Berhasil melakukan transaksi, silahkan upload bukti transfer untuk konfirmasi pendaftaran. Cek riwayat transaksi untuk upload.');
            } else {
                return FormatResponseJson::error(null, 'Gagal melakukan transaksi', 400);
            }
        } catch (\Exception $e) {
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

    public function uploadTransaction(Request $request)
    {
        try {
            $request->validate([
                'file_upload_transaction' => 'required|mimes:png,jpg'
            ]);
            $file = $request->file('file_upload_transaction');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $file_name);
            return FormatResponseJson::success($file,'Transaction fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan upload bukti transaksi', 500);
        }
    }
}
