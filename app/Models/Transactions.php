<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "transactions";
    protected $guarded = [];
    protected $hidden = [];

    public function event()
    {
        return $this->belongsTo(Events::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactionDetail()
    {
        // return $this->belongsTo(IuranTransactionDetail::class, 'id', 'tr_iuran_id');
        return $this->hasMany(TransactionDetail::class,'transaction_id',  'id');
    }
}
