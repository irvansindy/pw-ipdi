<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExportAttendance implements FromCollection, WithHeadings
{
    protected $category;
    protected $startDate;
    protected $endDate;

    public function __construct($category = null, $startDate = null, $endDate = null)
    {
        $this->category = $category;
        $this->startDate = $startDate ? Carbon::parse($startDate)->toDateString() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->toDateString() : null;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TransactionDetail::with(['transactions.event.category', 'transactions.attendanceLogs', 'user.dialisis'])
            ->where('is_attendanced', 1)
            ->when($this->category, function ($query) {
                $query->whereHas('transactions.event', function ($q) {
                    $q->where('category_event_id', $this->category);
                });
            })
            ->when($this->startDate || $this->endDate, function ($query) {
                $query->whereHas('transactions.attendanceLogs', function ($q) {
                    if ($this->startDate) {
                        $q->whereDate('attendance_date', '>=', $this->startDate);
                    }

                    if ($this->endDate) {
                        $q->whereDate('attendance_date', '<=', $this->endDate);
                    }
                });
            })
            ->get()
            ->map(function ($item) {
                $attendanceLogs = optional($item->transactions)->attendanceLogs ?? collect();

                if ($this->startDate) {
                    $attendanceLogs = $attendanceLogs->filter(function ($log) {
                        return Carbon::parse($log->attendance_date)->toDateString() >= $this->startDate;
                    });
                }

                if ($this->endDate) {
                    $attendanceLogs = $attendanceLogs->filter(function ($log) {
                        return Carbon::parse($log->attendance_date)->toDateString() <= $this->endDate;
                    });
                }

                $latestAttendance = $attendanceLogs->sortByDesc(function ($log) {
                    return Carbon::parse($log->attendance_date)->format('Y-m-d') . ' ' . Carbon::parse($log->attendance_time)->format('H:i:s');
                })->first();

                return [
                    'Tanggal Hadir' => $latestAttendance
                        ? Carbon::parse($latestAttendance->attendance_date)->format('Y-m-d') . ' ' . Carbon::parse($latestAttendance->attendance_time)->format('H:i:s')
                        : ($item->last_attendance_at
                            ? Carbon::parse($item->last_attendance_at)->format('Y-m-d H:i:s')
                            : $item->updated_at->format('Y-m-d H:i:s')),
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
