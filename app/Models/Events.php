<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Events extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'events';
    protected $guarded = [];
    protected $hidden = [];

    protected $casts = [
        'date' => 'datetime',      // Field yang sudah ada: date (start)
        'date_end' => 'datetime',  // Field yang sudah ada: date_end
    ];

    // Observer untuk auto calculate total_days
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($event) {
            // Auto calculate total_days dari date sampai date_end
            if ($event->date && $event->date_end) {
                $start = Carbon::parse($event->date);
                $end = Carbon::parse($event->date_end);
                $event->total_days = $start->diffInDays($end) + 1; // +1 karena include hari terakhir

                // Jika max_attendance tidak diisi, default = total_days
                if (!$event->max_attendance) {
                    $event->max_attendance = $event->total_days;
                }
            } else if ($event->date && !$event->date_end) {
                // Jika hanya ada date (event 1 hari)
                $event->total_days = 1;
                $event->max_attendance = 1;
            }
        });
    }

    public function category()
    {
        return $this->hasOne(CategoriesEvent::class, "id", "category_event_id");
    }
    public function eventDetail()
    {
        return $this->hasMany(EventDetail::class,"event_id", "id");
    }
    public function hotel()
    {
        return $this->hasOne(EventHotel::class, "event_id", "id");
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

        if (!$this->date) {
            return false;
        }

        $startDate = Carbon::parse($this->date)->toDateString();
        $endDate = $this->date_end ? Carbon::parse($this->date_end)->toDateString() : $startDate;

        return $now >= $startDate && $now <= $endDate;
    }

    /**
     * Get hari ke berapa dari event (relative to date)
     */
    public function getCurrentDay()
    {
        if (!$this->date) {
            return null;
        }

        $start = Carbon::parse($this->date);
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
        $endDate = $this->date_end ? Carbon::parse($this->date_end) : Carbon::parse($this->date);
        $now = Carbon::now();

        if ($now->gt($endDate)) {
            return 0; // Sudah selesai
        }

        return $now->diffInDays($endDate) + 1;
    }

    /**
     * Get formatted start date
     */
    public function getStartDateAttribute()
    {
        return $this->date ? Carbon::parse($this->date) : null;
    }

    /**
     * Get formatted end date
     */
    public function getEndDateAttribute()
    {
        return $this->date_end ? Carbon::parse($this->date_end) : Carbon::parse($this->date);
    }
}
