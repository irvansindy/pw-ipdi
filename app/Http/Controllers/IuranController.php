<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\User;
use App\Models\IuranTransaction;
use App\Models\IuranTransactionDetail;
class IuranController extends Controller
{
    public function index()
    {
        return view('pages.user.iuran.index');
    }
    public function fetchIuran()
    {
        try {
            $iuran = Iuran::orderBy('id','desc')->get();
            return FormatResponseJson::success($iuran, 'iuran fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function peserta()
    {
        try {
            // $peserta = User::with('roles')->whereHas('roles', function ($query) {
            $peserta = User::whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })
            ->get(['id', 'username']);
            
            return FormatResponseJson::success($peserta, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function generatePaymentIuran(Request $request)
    {
        try {
            $data_user = $request->iuran_type == 'mandiri' ? $data_user = [\Auth::user()->id] : $request->list_peserta;
            // dd($data_user);
            if ($request->iuran_type == 'kolektif') {
                if ($request->list_peserta == null) {
                    return FormatResponseJson::error(null, 'Peserta tidak boleh kosong', 402);
                }
            }
            $existing_user = User::whereIn('id', $data_user)->get([
                'id',
                'username',
                'email',
                'status',
            ]);
            // dd(count($existing_user));
            $total_payment = [];
            for ($i = 0; $i < count($existing_user); $i++) {
                $current_payment = $existing_user[$i]->status == 1 ? 50000 : 75000;
                $total_payment[$i] = $current_payment;
            }
            $grand_total = collect($total_payment)->sum();
            return FormatResponseJson::success($grand_total, 'generate user payment iuran successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function storeIuran(Request $request)
    {
        try {
            // dd($request->all());
            $check_iuran = IuranTransaction::count();
            $code_trans = 'IURAN';
            $date_trans = now()->format('y/m/d');
            $numbering =  $check_iuran + 1;
            $result_number = $code_trans.'-'.$date_trans.'/'.$numbering;
            $iuran_type = $request->iuran_type == 'mandiri' ? 0 : 1;
            $request->validate([
                'iuran_type' => 'required'
            ]);

            // create master iuran transaction
            $master_iuran = IuranTransaction::create([
                'iuran_number' => $result_number,
                'iuran_id' => $iuran_type,
                'user_id' => \Auth::user()->id,
                'grand_total' => $request->val_grand_total_payment_iuran,
                'date' => date('Y-m-d H:i:s'),
            ]);
            
            if($iuran_type == 0 && $request->list_peserta == null) {
                $list_peserta = [
                    'tr_iuran_id' => $master_iuran->id,
                    'user_id' => \Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                IuranTransactionDetail::insert($list_peserta);
            } else if($iuran_type == 1 && $request->list_peserta != null) {
                if (count($request->list_peserta) > 0) {
                    $data_peserta = [];
                    $list_peserta = [];
                    for ($i=0; $i < count($request->list_peserta); $i++) { 
                        $list_peserta = [
                            'tr_iuran_id' => $master_iuran->id,
                            'user_id' => $request->list_peserta[$i],
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
    
                        array_push($data_peserta, $list_peserta);
                    }
                    // dd($data_peserta);
                    $create_peserta = IuranTransactionDetail::insert($data_peserta);
                } else {
                    return FormatResponseJson::error(null, 'Silahkan isi data form yang masih kosong',400);
                }
            }

            return FormatResponseJson::success($master_iuran, 'iuran created successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
            // return FormatResponseJson::error(null, 'Gagal, silahkan hubungi admin.', 404);
        }
    }
    public function fetchTransaksiIuran()
    {
        try {
            $transaksi = IuranTransactionDetail::with(['user', 'iuran'])
            ->whereHas('user', function($query) {
                $query->where('id','=', Auth::user()->id);
            })->get();
            // dd($transaksi);
            return FormatResponseJson::success($transaksi,'transaksi fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(),404);
        }
    }
    public function detailTransaksiIuran(Request $request)
    {
        try {
            $transaksi = IuranTransaction::with(['user','transactionDetail.user'])
            ->where('id','=', $request->iuran_id)
            // ->where('id','=', 8)
            ->get();
            return FormatResponseJson::success($transaksi,'transaksi fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(),404);
        }
    }
    public function uploadIuran(Request $request)
    {
        try {
            // dd($request->file('file_iuran_data'));
            $request->validate([
                'file_iuran_data' => 'required|mimes:png,jpg,pdf'
            ]);
            
            $file = $request->file('file_iuran_data');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            
            $transaction = IuranTransaction::find($request->master_iuran_id);
            // dd($transaction);
            $transaction->update([
                'status' => 'progress',
                'iuran_image_transfer' => $file_name
            ]);

            $file->move(public_path('uploads/bukti_transaksi/iuran'), $file_name);
            return FormatResponseJson::success($transaction,'file uploaded successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan upload, silahkan hubungi admin', 500);
            // return FormatResponseJson::error(null, $e->getMessage(),500);
        }
    }
}
