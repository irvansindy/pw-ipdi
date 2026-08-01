<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExportAttendance implements FromCollection, WithHeadings
{
    protected $category;
    protected $year;

    public function __construct($category = null, $year = null)
    {
        $this->category = $category;
        $this->year = $year;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TransactionDetail::with(['transactions.event.category', 'user.dialisis'])
            ->where('is_attendanced', 1)
            ->when($this->category, function ($query) {
                $query->whereHas('transactions.event', function ($q) {
                    $q->where('category_event_id', $this->category);
                });
            })
            ->when($this->year, function ($query) {
                $query->whereYear('created_at', $this->year);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal Hadir' => $item->updated_at->format('Y-m-d H:i:s'),
                    'Nomor Transaksi' => $item->transactions->number ?? '-',
                    'Nama Event' => $item->transactions->event->title ?? '-',
                    'Nama Peserta' => $item->user->username ?? '-',
                    'Rumah Sakit' => $item->user->dialisis->name ?? '-',
                ];
            });
    }
    public function headings(): array
    {
        return [
            'Tanggal Hadir',
            'Nomor Transaksi',
            'Nama Event',
            'Nama Peserta',
            'Rumah Sakit',
        ];
    }
}
