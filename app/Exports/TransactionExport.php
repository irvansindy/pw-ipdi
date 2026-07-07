<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionExport implements FromCollection, WithHeadings, WithColumnFormatting
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
                b.nira_ipdi,
                b.email,
                b.number_phone,
                b.nama_perusahaan,
                b.center_dialisis,
                c.number,
                c.proof_of_payment
            FROM transaction_details a
            JOIN users b ON a.user_id = b.id
            JOIN transactions c ON a.transaction_id = c.id
            JOIN events e ON c.event_id = e.id
            JOIN categories_events cat ON e.category_event_id = cat.id
            WHERE cat.name = ?
            AND YEAR(c.created_at) = ?", [$this->category, $this->year]
        );

        // Ubah hasil ke collection dengan link file bukti transaksi
        $data = collect($list_transaction)->map(function ($item) {
            return [
                $item->username,
                $item->nik,
                $item->nira_ipdi,
                $item->email,
                $item->number_phone,
                $item->nama_perusahaan,
                $item->center_dialisis,
                $item->number,
                url('uploads/bukti_transaksi/' . $item->proof_of_payment), // gunakan url() agar bisa diakses di browser
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIK',
            'NIRA IPDI',
            'Email',
            'Nomor Telp',
            'Asal Instansi',
            'Asal Dialisis',
            'Nomor Transaksi',
            'Bukti Transaksi (Link)',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // NIK
            'C' => NumberFormat::FORMAT_TEXT, // NIRA IPDI
        ];
    }
}
