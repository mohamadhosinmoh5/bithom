<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Answer extends Model
{
    protected $fillable = [

        'answer',
        'user_id',
        'ticket_id',
        'message_id'
    ];


    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'answer_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }



}
