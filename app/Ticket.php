<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{
    protected $fillable = [
        'idUser',
        'ticket'
    ];


    public function answer()
    {
        return $this->hasMany(Answer::class, 'idTicket', 'id');
    }

    public function message()
    {
        return $this->hasMany(Message::class, 'idTicket', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'idUser' ,'id');
    }
}
