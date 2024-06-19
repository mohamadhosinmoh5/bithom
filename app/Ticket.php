<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'ticket'
    ];


    public function answer()
    {
        return $this->hasMany(Answer::class, 'ticket_id', 'id');
    }

    public function message()
    {
        return $this->hasMany(Message::class, 'ticket_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' ,'id');
    }
}
