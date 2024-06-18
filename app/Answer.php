<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Answer extends Model
{
    protected $fillable = [

        'answer',
        'idUser',
        'idTicket'
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
