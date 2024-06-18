<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Wallet extends Model
{
    protected $fillable = [
        'idUser',
        'stock'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'idWallet', 'id');
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'idWallet' ,'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

}
