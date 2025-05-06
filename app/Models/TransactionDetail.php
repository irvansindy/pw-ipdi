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
        'user_id',
        'is_attendanced'
    ];
    public function user()
    {
        // return $this->belongsTo(User::class, "user_id", "id");
        return $this->hasOne(User::class, "id", "user_id");
    }
    public function transactions()
    {
        return $this->hasOne(Transactions::class, "id","transaction_id");
        // return $this->belongsTo(Transactions::class,"transaction_id", "id");
    }
}
