<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    protected $category;
    protected $year;

    public function __construct($category, $year)
    {
        $this->category = $category;
        $this->year = $year;
    }

    public function collection()
    {
        $list_transaction = DB::select(
            "SELECT 
                b.username, 
                b.nik, 
                b.email, 
                b.number_phone, 
                b.nama_perusahaan, 
                b.center_dialisis, 
                c.number 
            FROM transaction_details a
            JOIN users b ON a.user_id = b.id
            JOIN transactions c ON a.transaction_id = c.id
            JOIN events e ON c.event_id = e.id
            JOIN categories_events cat ON e.category_event_id = cat.id
            WHERE cat.name = ? 
            AND YEAR(c.created_at) = ?", [$this->category, $this->year]
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
