<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'meterage',
        'project_id',
        'user_id',
        'date',
        'time',
        'brick_number',
        'brick_price',
        'wallet_id',
        'investment_price',
        'investment_meterage'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
