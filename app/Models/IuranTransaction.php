<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranTransaction extends Model
{
    use HasFactory;
    protected $table = 'iuran_transactions';
    // protected $fillable = [
    //     "iuran_number",
    //     "iuran_id",
    //     "user_id",
    //     "iuran_image_transfer",
    //     "date"
    // ];
    protected $guarded = [];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function transactionDetail()
    {
        // return $this->belongsTo(IuranTransactionDetail::class, 'id', 'tr_iuran_id');
        return $this->hasMany(IuranTransactionDetail::class,'tr_iuran_id',  'id');
    }
}
