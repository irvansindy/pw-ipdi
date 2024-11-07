<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranTransactionDetail extends Model
{
    use HasFactory;
    protected $table = "iuran_transaction_details";
    protected $fillable = [
        "tr_iuran_id",
        "user_id",
    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
        // return $this->hasMany(User::class, "id", "user_id");
    }
    public function iuran()
    {
        return $this->hasOne(IuranTransaction::class, "id","tr_iuran_id");
    }
}
