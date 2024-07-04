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
        'amount',
        'status',
        'reference_code',
        'trackId'

    ];

    const SUCCESSFUL = "1";
    const UNSUCCESSFUL = "0";
    const DIRECT = "direct";
    const WALLET = "wallet";


    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id' ,'id');
    }

}
