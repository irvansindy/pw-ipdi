<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'transaction_details';
    protected $fillable = [
        'transaction_id',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
        // return $this->hasMany(User::class, "id", "user_id");
    }
    public function transactions()
    {
        return $this->hasMany(Transactions::class, "id","transaction_id");
    }
}
