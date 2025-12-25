<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'file_name',
        'file_path',
        'description',
        'file_type',
        'file_size',
        'mime_type'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }
}