<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryEvent extends Model
{
    use HasFactory;
    protected $table = 'sub_category_events';
    protected $guarded = [];
    protected $hidden = [];

}
