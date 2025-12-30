<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\CategoriesEvent;
use App\Models\AttendanceLog;
use App\Helpers\FormatResponseJson;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportAttendanceDetailed;
use Carbon\Carbon;

class ScreeningQrCodeAudience extends Controller
{
    public function index()
    {
        return view('pages.admin.attendance_audience.index');
    }

    public function viewListAudience(Request $request)
    {
        $categories = CategoriesEvent::all();
        return view('pages.admin.attendance_audience.list', compact('categories'));
    }

    public function getDataAudienceById(Request $request)
    {
        try {
            $id = $request->input('id');

            $data = Transactions::with(['event', 'user.dialisis', 'transactionDetail'])
                ->where('number', $id)
                ->orWhere('token_register', $id)
                ->first();

            if (!$data) {
                return FormatResponseJson::error(null, 'Data transaksi tidak ditemukan', 404);
            }

            $event = $data->event;
            $transactionDetail = $data->transactionDetail[0] ?? null;

            if (!$transactionDetail) {
                return FormatResponseJson::error(null, 'Detail transaksi tidak ditemukan', 404);
            }

            // Validasi: Cek apakah event memiliki date
            if (!$event->date) {
                return FormatResponseJson::error([
                    'message' => 'Event ini belum dikonfigurasi dengan tanggal'
                ], 'Konfigurasi event tidak lengkap', 400);
            }

            $userId = $data->user_id;
            $today = Carbon::now()->toDateString();
            $eventStart = Carbon::parse($event->date)->toDateString();
            $eventEnd = $event->date_end
                ? Carbon::parse($event->date_end)->toDateString()
                : $eventStart; // Jika date_end null, berarti event 1 hari

            // VALIDASI 1: Cek apakah hari ini dalam rentang event
            if ($today < $eventStart) {
                return FormatResponseJson::error([
                    'message' => 'Event belum dimulai. Mulai: ' . Carbon::parse($eventStart)->format('d-m-Y'),
                    'event_start' => $eventStart,
                    'today' => $today
                ], 'Event belum dimulai', 400);
            }

            if ($today > $eventEnd) {
                return FormatResponseJson::error([
                    'message' => 'Event sudah berakhir. Selesai: ' . Carbon::parse($eventEnd)->format('d-m-Y'),
                    'event_end' => $eventEnd,
                    'today' => $today
                ], 'Event sudah berakhir', 400);
            }

            // VALIDASI 2: Cek apakah sudah absen hari ini
            $hasAttendedToday = AttendanceLog::hasAttendedToday($data->id, $userId, $today);

            if ($hasAttendedToday) {
                $todayLog = AttendanceLog::where('transaction_id', $data->id)
                    ->where('user_id', $userId)
                    ->whereDate('attendance_date', $today)
                    ->first();

                return FormatResponseJson::error([
                    'message' => 'Anda sudah melakukan absensi hari ini',
                    'attendance_date' => $today,
                    'attendance_time' => $todayLog->attendance_time,
                    'data' => $data
                ], 'Sudah absen hari ini', 400);
            }

            // VALIDASI 3: Cek apakah sudah mencapai batas maksimal
            $currentCount = AttendanceLog::getAttendanceCount($data->id, $userId);
            $maxAttendance = $event->max_attendance ?? $event->total_days ?? 1;

            if ($currentCount >= $maxAttendance) {
                return FormatResponseJson::error([
                    'message' => "Anda sudah mencapai batas maksimal absensi ($maxAttendance hari)",
                    'current_attendance' => $currentCount,
                    'max_attendance' => $maxAttendance,
                    'data' => $data
                ], 'Batas maksimal absensi tercapai', 400);
            }

            // Hitung hari ke berapa dari event
            $eventDayNumber = Carbon::parse($eventStart)->diffInDays(Carbon::parse($today)) + 1;

            // Hitung absensi user ke berapa
            $userAttendanceNumber = $currentCount + 1;

            // PROSES ABSENSI
            $attendanceLog = AttendanceLog::create([
                'transaction_id' => $data->id,
                'user_id' => $userId,
                'event_id' => $event->id,
                'attendance_date' => $today,
                'attendance_time' => Carbon::now()->format('H:i:s'),
                'day_number' => $userAttendanceNumber,
                'scanned_code' => $id
            ]);

            // Update transaction_details
            $newCount = $currentCount + 1;
            $attendanceCompleted = ($newCount >= $maxAttendance);

            $transactionDetail->update([
                'is_attendanced' => 1,
                'attendance_count' => $newCount,
                'last_attendance_at' => Carbon::now(),
                'attendance_completed' => $attendanceCompleted
            ]);

            // Tambahkan info kehadiran ke response
            $data->attendance_info = [
                'user_attendance_number' => $userAttendanceNumber,
                'event_day_number' => $eventDayNumber,
                'total_attendance' => $newCount,
                'max_attendance' => $maxAttendance,
                'remaining_attendance' => max(0, $maxAttendance - $newCount),
                'attendance_date' => $today,
                'attendance_time' => Carbon::now()->format('H:i:s'),
                'is_completed' => $attendanceCompleted,
                'event_info' => [
                    'total_days' => $event->total_days ?? 1,
                    'start_date' => Carbon::parse($event->date)->format('d-m-Y'),
                    'end_date' => $event->date_end
                        ? Carbon::parse($event->date_end)->format('d-m-Y')
                        : Carbon::parse($event->date)->format('d-m-Y'),
                ]
            ];

            $message = "Berhasil mencatat kehadiran - Hari ke-{$userAttendanceNumber} dari {$maxAttendance}";

            if ($attendanceCompleted) {
                $message = "🎉 SELAMAT! Anda telah menyelesaikan absensi untuk semua hari event!";
            }

            return FormatResponseJson::success($data, $message);

        } catch (\Exception $e) {
            return FormatResponseJson::error([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 'Gagal mendapatkan data transaksi', 500);
        }
    }

    public function fetchDataIsAttendance(Request $request)
    {
        try {
            $categoryId = $request->input('category');
            $year = $request->input('year');

            $data = Transactions::with([
                'event.category',
                'user.dialisis',
                'transactionDetail',
                'attendanceLogs'
            ])
            ->whereHas('transactionDetail', function ($query) {
                $query->where('is_attendanced', 1);
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->whereHas('event.category', function ($q) use ($categoryId) {
                    $q->where('id', $categoryId);
                });
            })
            ->when($year, function ($query, $year) {
                $query->whereYear('created_at', $year);
            })
            ->get();

            // Tambahkan info attendance
            $data->map(function ($item) {
                $detail = $item->transactionDetail[0] ?? null;
                $item->total_attendance = $item->attendanceLogs->count();
                $item->attendance_completed = $detail ? $detail->attendance_completed : false;
                $item->max_attendance = $item->event->max_attendance ?? $item->event->total_days ?? 1;
                $item->attendance_dates = $item->attendanceLogs->pluck('attendance_date')->map(function($date) {
                    return Carbon::parse($date)->format('d-m-Y');
                })->toArray();
                return $item;
            });

            return FormatResponseJson::success($data, 'Berhasil mendapatkan status kehadiran');

        } catch (\Exception $e) {
            return FormatResponseJson::error([
                'error' => $e->getMessage()
            ], 'Gagal mendapatkan status kehadiran', 400);
        }
    }

    public function export(Request $request)
    {
        $category = $request->input('category');
        $year = $request->input('year');

        $fileName = 'attendance_detailed_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ExportAttendanceDetailed($category, $year), $fileName);
    }

    /**
     * API untuk cek history kehadiran per user
     */
    public function getAttendanceHistory(Request $request, $transactionId)
    {
        try {
            $logs = AttendanceLog::where('transaction_id', $transactionId)
                ->with('user', 'event')
                ->orderBy('attendance_date', 'asc')
                ->get();

            return FormatResponseJson::success($logs, 'Berhasil mendapatkan history kehadiran');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal mendapatkan history kehadiran', 400);
        }
    }

    /**
     * Get statistik kehadiran untuk dashboard
     */
    public function getAttendanceStatistics(Request $request, $eventId)
    {
        try {
            $event = Events::with(['attendanceLogs', 'transactions'])->findOrFail($eventId);

            $totalRegistered = $event->transactions()->count();
            $totalAttended = $event->transactions()
                ->whereHas('transactionDetail', function($q) {
                    $q->where('is_attendanced', 1);
                })
                ->count();

            $completedAttendance = $event->transactions()
                ->whereHas('transactionDetail', function($q) {
                    $q->where('attendance_completed', 1);
                })
                ->count();

            $statistics = [
                'event_info' => [
                    'title' => $event->title,
                    'total_days' => $event->total_days ?? 1,
                    'max_attendance' => $event->max_attendance ?? $event->total_days ?? 1,
                    'start_date' => $event->date ? Carbon::parse($event->date)->format('d-m-Y') : '-',
                    'end_date' => $event->date_end
                        ? Carbon::parse($event->date_end)->format('d-m-Y')
                        : ($event->date ? Carbon::parse($event->date)->format('d-m-Y') : '-'),
                    'is_ongoing' => $event->isOngoing(),
                ],
                'participants' => [
                    'total_registered' => $totalRegistered,
                    'total_attended' => $totalAttended,
                    'completed_all_days' => $completedAttendance,
                    'attendance_rate' => $totalRegistered > 0
                        ? round(($totalAttended / $totalRegistered) * 100, 2)
                        : 0,
                ],
                'daily_stats' => AttendanceLog::getEventStatistics($eventId),
            ];

            return FormatResponseJson::success($statistics, 'Berhasil mendapatkan statistik');

        } catch (\Exception $e) {
            return FormatResponseJson::error([
                'error' => $e->getMessage()
            ], 'Gagal mendapatkan statistik', 400);
        }
    }
}