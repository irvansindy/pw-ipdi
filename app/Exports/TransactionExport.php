<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class TransactionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return TransactionDetail::with(['user', 'transactions']);
        $list_transaction = DB::select(
            "SELECT b.username, b.nik, b.email, b.number_phone, b.nama_perusahaan, b.center_dialisis, c.number 
                FROM transaction_details a
                JOIN users b ON a.user_id = b.id
                JOIN transactions c ON a.transaction_id = c.id"
        );
        return collect($list_transaction);
    }
    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIK',
            'Email',
            'Nomor Telp',
            'Asal Instansi',
            'Asal Dialisis',
            'Nomor Transaksi',
        ];
    }
}
