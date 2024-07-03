<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'date',
        'time',
        'transaction_type',
        'price',
        'status',
        'reference_code'

    ];
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id' ,'id');
    }

}
