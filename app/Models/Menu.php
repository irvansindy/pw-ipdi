<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menus';
    protected $guarded = [];
    public function permissions()
    {
        return $this->hasMany(\Spatie\Permission\Models\Permission::class, 'name','permission');
    }
}
