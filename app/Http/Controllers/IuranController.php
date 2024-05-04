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
            ->get();
            
            return FormatResponseJson::success($peserta, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function storeIuran(Request $request)
    {
        try {
            $check_iuran = IuranTransaction::count();
            $code_trans = 'IURAN';
            $date_trans = now()->format('y/m/d');
            $numbering =  $check_iuran + 1;
            $result_number = $code_trans.'-'.$date_trans.'/'.$numbering;
            $request->validate([
                'iuran_type' => 'required'
            ]);
            $iuran = IuranTransaction::create([
                'iuran_number' => $result_number,
                'iuran_id' => $request->create_iuran_id,
                'user_id' => \Auth::user()->id,
                'date' => date('Y-m-d H:i:s'),
            ]);

            if (count($request->list_peserta) > 0) {
                $data_peserta = [];
                $list_peserta = [];
                for ($i=0; $i < count($request->list_peserta); $i++) { 
                    $list_peserta = [
                        'tr_iuran_id' => $iuran->id,
                        'user_id' => $request->list_peserta[$i],
                        'created_at' => date('Y-m-d H:i:s'),
                    ];

                    array_push($data_peserta, $list_peserta);
                }
                // dd($data_peserta);
                $create_address = IuranTransactionDetail::insert($data_peserta);
            }

            return FormatResponseJson::success($iuran, 'iuran created successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
            // return FormatResponseJson::error(null, 'Gagal, silahkan hubungi admin.', 404);
        }
    }
    public function fetchTransaksiIuran()
    {
        try {
            $transaksi = IuranTransactionDetail::with(['user', 'transactions'])
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
            $request->validate([
                'upload_iuran' => 'required|mimes:png,jpg,pdf'
            ]);
            
            $file = $request->file('upload_iuran');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti_transaksi/iuran'), $file_name);
            $transaction = IuranTransaction::find($request->id);
            // dd($transaction);
            $transaction->update([
                // 'status' => 'progress',
                'iuran_image_transfer' => $file_name
            ]);
            return FormatResponseJson::success($transaction,'file uploaded successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan upload, silahkan hubungi admin', 500);
            // return FormatResponseJson::error(null, $e->getMessage(),500);
        }
    }
}
