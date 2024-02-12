<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use App\Models\Transactions;


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
            $transaction = Transactions::with(['event', 'user'])->findOrFail($request->id);
            return FormatResponseJson::success($transaction, 'Transaction detail fetched successfully');
        } catch (\Throwable $th) {
            return FormatResponseJson::error(null, $th->getMessage(), 400);
        }
    }
}
