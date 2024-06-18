<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'meterage',
        'idProject',
        'idUser',
        'date',
        'time',
        'khesht',
        'kheshtPrice',
        'idWallet',
        'investmentPrice'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'idProject', 'id');
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'idWallet', 'id');
    }
}
