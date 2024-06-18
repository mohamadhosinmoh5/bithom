<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    protected $fillable = [
        'idWallet',
        'date',
        'time',
        'transactionType',
        'price',
        'status'

    ];
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'idWallet' ,'id');
    }

}
