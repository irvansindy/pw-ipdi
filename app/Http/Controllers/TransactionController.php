<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Events;
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

                $fetch_currect_event = Events::find($event_id);
                $data = [
                    $check_transaction,
                    $fetch_currect_event
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
                return FormatResponseJson::success($create_transaction, 'Berhasil melakukan transaksi, silahkan cek email anda.');
            } else {
                return FormatResponseJson::error(null, 'Gagal melakukan transaksi', 400);
            }
            // $data = [
            //     $check_total_current_transaction,
            //     $check_quota_event
            // ];
            // return FormatResponseJson::success($data, 'Transaction created successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
}
