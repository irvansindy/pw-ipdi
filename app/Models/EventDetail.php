<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    use HasFactory;

    protected $table = 'event_details';
    protected $guarded = [];
    protected $casts = [
        'price' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id', 'id');
    }
}
