<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Events extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date' => 'datetime',
    ];

    // Observer untuk auto calculate total_days
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            if ($event->start_date && $event->end_date) {
                $start = Carbon::parse($event->start_date);
                $end = Carbon::parse($event->end_date);
                $event->total_days = $start->diffInDays($end) + 1; // +1 karena include hari terakhir

                // Jika max_attendance tidak diisi, default = total_days
                if (!$event->max_attendance) {
                    $event->max_attendance = $event->total_days;
                }
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(CategoriesEvent::class, 'category_event_id', 'id');
    }

    public function EventDetail()
    {
        return $this->hasMany(EventDetail::class, 'event_id', 'id');
    }

    public function hotel()
    {
        return $this->hasOne(EventHotel::class, 'event_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'event_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'event_id');
    }

    /**
     * Check apakah event sedang berlangsung
     */
    public function isOngoing()
    {
        $now = Carbon::now()->toDateString();
        return $this->start_date && $this->end_date &&
               $now >= $this->start_date->toDateString() &&
               $now <= $this->end_date->toDateString();
    }

    /**
     * Get hari ke berapa dari event (relative to start_date)
     */
    public function getCurrentDay()
    {
        if (!$this->start_date) {
            return null;
        }

        $start = Carbon::parse($this->start_date);
        $now = Carbon::now();

        if ($now->lt($start)) {
            return 0; // Belum mulai
        }

        return $start->diffInDays($now) + 1;
    }

    /**
     * Get sisa hari event
     */
    public function getRemainingDays()
    {
        if (!$this->end_date) {
            return null;
        }

        $end = Carbon::parse($this->end_date);
        $now = Carbon::now();

        if ($now->gt($end)) {
            return 0; // Sudah selesai
        }

        return $now->diffInDays($end) + 1;
    }
}