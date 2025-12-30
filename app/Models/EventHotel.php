<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventHotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'name',
        'price',
    ];
    protected $casts = [
    'price' => 'integer', // atau 'float'
];
    

}
