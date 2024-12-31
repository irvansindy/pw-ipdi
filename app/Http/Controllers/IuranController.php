<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;
use App\Models\User;
use App\Models\IuranTransaction;
use App\Models\IuranTransactionDetail;
use Illuminate\Support\Str;
class IuranController extends Controller
{
    public function index()
    {
        return view('pages.user.iuran.index');
    }
    public function viewCreateIuran()
    {
        return view('pages.user.iuran.create_iuran_anggota');
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
            $peserta = User::with(['roles', 'dialisis'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })
            ->get();
            
            return FormatResponseJson::success($peserta, 'user fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function generatePaymentIuran(Request $request)
    {
        try {
            $total_iuran_20k = collect($request->iuran_20k)->sum();
            $total_iuran_50k = collect($request->iuran_50k)->sum();
            $total_payment = $total_iuran_20k + $total_iuran_50k;
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
            
            $total_iuran_20k = collect($request->iuran_20k)->sum();
            $total_iuran_50k = collect($request->iuran_50k)->sum();
            $grand_total = $total_iuran_20k + $total_iuran_50k;

            $file_iuran_file_upload = $request->file('iuran_file_upload');
            $slug_name = Str::slug($file_iuran_file_upload->getClientOriginalName().' iuran', '-');
            $file_iuran_file_upload_name = $slug_name.'.'.$file_iuran_file_upload->getClientOriginalExtension();
            // dd($file_iuran_file_upload->getClientOriginalName());

            // create master iuran transaction
            $master_iuran = IuranTransaction::create([
                'iuran_number' => $result_number,
                'iuran_id' => $iuran_type,
                'user_id' => \Auth::user()->id,
                'grand_total' => $grand_total,
                'iuran_image_transfer' => 'storage/iuran/'.$file_iuran_file_upload_name,
                'date' => date('Y-m-d H:i:s'),
                'for_date' => date('Y'),
                'status' => 'progress'
            ]);

            if ($master_iuran) {
                $file_iuran_file_upload->move(public_path('storage/iuran/'), $file_iuran_file_upload_name);
            }

            if (count($request->peserta) > 0) {
                # code...
                for ($i=0; $i < count($request->peserta); $i++) { 
                    $data_peserta = [];
                    $list_peserta = [];
                    $list_peserta = [
                        'tr_iuran_id' => $master_iuran->id,
                        'user_id' => $request->peserta[$i],
                        'tahun_iuran' => $request->tahun_pembayaran_iuran[$i],
                        'biaya_regis_anggota_baru' => $request->iuran_20k[$i],
                        'biaya_iuran_tahunan' => $request->iuran_50k[$i],
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    array_push($data_peserta, $list_peserta);
                    $create_peserta = IuranTransactionDetail::insert($data_peserta);
                }
            } else {
                return FormatResponseJson::error(null, 'Silahkan isi data form yang masih kosong',400);
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
