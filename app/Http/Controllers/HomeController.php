<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\User;
use App\Models\IuranTransaction;
use App\Models\IuranTransactionDetail;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
class HomeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pages.dashboard');
    }

    public function fetchMember()
    {
        try {
            $laki_laki = User::where('jenis_kelamin', 'L')->count();
            $perempuan = User::where('jenis_kelamin', 'P')->count();
            $member = [
                'labels' => [
                    'laki_laki',
                    'perempuan'
                ],
                'member' => [
                    $laki_laki,
                    $perempuan
                ],
                'color' => [
                    '#EB5A3C',
                    '#FFC700'
                ]
            ];
            return FormatResponseJson::success($member, 'member fetched successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function paymentIuran(Request $request)
    {
        try {
            // Ambil tahun dari request, jika tidak ada gunakan tahun sekarang
            $year = $request->input('year', date('Y'));

            // Validasi tahun (hanya antara 2020-2025)
            if (!in_array($year, range(2020, 2025))) {
                return response()->json(['error' => 'Tahun tidak valid'], 400);
            }

            $user_paid = User::leftJoin('iuran_transaction_details', 'users.id', '=', 'iuran_transaction_details.user_id')
            ->select(
                'users.id as user_id', 
                'users.username as user_name', 
                'iuran_transaction_details.user_id',
                'iuran_transaction_details.tahun_iuran', 
            )
            ->where('iuran_transaction_details.tahun_iuran', '!=', null)
            ->where('iuran_transaction_details.tahun_iuran', '=', '2024')
            ->where('iuran_transaction_details.user_id', '!=', null)
            ->count();
            
            $user_unpaid = User::leftJoin('iuran_transaction_details', 'users.id', '=', 'iuran_transaction_details.user_id')
            ->select(
                'users.id as user_id', 
                'users.username as user_name', 
                'iuran_transaction_details.user_id',
                'iuran_transaction_details.tahun_iuran', 
            )
            ->where('iuran_transaction_details.tahun_iuran', '!=', null)
            ->where('iuran_transaction_details.tahun_iuran', '=', '2024')
            ->where('iuran_transaction_details.user_id', '=', null)
            ->count();

            $data_iuran = [
                'labels' => [
                    'Sudah Membayar',
                    'Belum Membayar'
                ],
                'iuran' => [
                    $user_paid,
                    $user_unpaid
                ],
                'year' => $year,
            ];

            return FormatResponseJson::success($data_iuran, 'paid fetched successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
    public function userJnns(Request $request)
    {
        try {
            $jnns_participants_present = TransactionDetail::leftJoin('users', 'transaction_details.user_id','=','users.id')
            ->leftJoin('transactions', 'transaction_details.transaction_id','=','transactions.id')
            ->where('is_attendanced', '=', 1)
            ->where('transactions.date', 'like', '%'. $request->input('year') .'%')
            ->count();
            $jnns_participants_not_attend = TransactionDetail::leftJoin('users', 'transaction_details.user_id','=','users.id')
            ->leftJoin('transactions', 'transaction_details.transaction_id','=','transactions.id')
            ->where('is_attendanced', '=', 0)
            ->where('transactions.date', 'like', '%'. $request->input('year') .'%')
            ->count();

            $data_jnns = [
                'labels' => [
                    'Hadir',
                    'Tidak Hadir',
                ],
                'jnns' => [
                    $jnns_participants_present,
                    $jnns_participants_not_attend,
                    // 100,
                    // 20
                ],
                'year'=> $request->input('yearFilterJnns'),
            ];
            return FormatResponseJson::success($data_jnns, 'paid fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }
}
