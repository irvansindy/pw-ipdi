<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'attendance_date',
        'attendance_time',
        'day_number',
        'scanned_code'
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

    /**
     * Check apakah user sudah absen di tanggal tertentu
     */
    public static function hasAttendedToday($transactionId, $userId)
    {
        return self::where('transaction_id', $transactionId)
            ->where('user_id', $userId)
            ->whereDate('attendance_date', now()->toDateString())
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
}