<?php

namespace App;

use App\Models\User;
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


    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTicket', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
