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
        return $this->hasOne(CategoriesEvent::class, "id");
    }
}
