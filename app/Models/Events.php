<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $hidden = [];

    public function category()
    {
        return $this->hasOne(CategoriesEvent::class, "id", "category_event_id");
        // return $this->belongsTo(CategoriesEvent::class);
    }
    public function eventDetail()
    {
        return $this->hasMany(EventDetail::class,"event_id", "id");
    }
    public function hotel()
    {
        return $this->hasOne(EventHotel::class, "event_id", "id");
    }
}
