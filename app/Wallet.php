<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'stock'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'wallet_id', 'id');
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'wallet_id' ,'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
