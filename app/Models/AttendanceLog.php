<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'event_id',
        'attendance_date',
        'attendance_time',
        'day_number',
        'scanned_code',
        'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attendance_time' => 'datetime:H:i:s',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Events::class);
    }

    /**
     * Check apakah user sudah absen di tanggal tertentu
     */
    public static function hasAttendedToday($transactionId, $userId, $date = null)
    {
        $checkDate = $date ?: Carbon::now()->toDateString();

        return self::where('transaction_id', $transactionId)
            ->where('user_id', $userId)
            ->whereDate('attendance_date', $checkDate)
            ->exists();
    }

    /**
     * Get total kehadiran untuk transaction tertentu
     */
    public static function getAttendanceCount($transactionId, $userId)
    {
        return self::where('transaction_id', $transactionId)
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Get attendance statistics untuk event
     */
    public static function getEventStatistics($eventId)
    {
        $logs = self::where('event_id', $eventId)->get();

        return [
            'total_scans' => $logs->count(),
            'unique_attendees' => $logs->unique('user_id')->count(),
            'by_date' => $logs->groupBy(function($item) {
                return $item->attendance_date->format('Y-m-d');
            })->map->count(),
        ];
    }

    /**
     * Check apakah sudah mencapai batas maksimal absen
     */
    public static function hasReachedMaxAttendance($transactionId, $userId, $maxAttendance)
    {
        $count = self::getAttendanceCount($transactionId, $userId);
        return $count >= $maxAttendance;
    }
}