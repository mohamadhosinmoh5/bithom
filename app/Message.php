<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    protected $fillable = [
        'idUser',
        'date',
        'status',
        'idTicket',
        'message'
    ];
}
