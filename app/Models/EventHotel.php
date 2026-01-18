<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHotel extends Model
{
    use HasFactory;

    protected $table = 'event_hotels';
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'location',
    ];
    protected $casts = [
        'price' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id', 'id');
    }
}
