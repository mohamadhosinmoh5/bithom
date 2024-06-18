<?php

namespace App;

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

}
