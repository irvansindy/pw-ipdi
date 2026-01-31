<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $guarded = [];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class, 'news_id')->orderBy('order', 'asc');
    }
}
