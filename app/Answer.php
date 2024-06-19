<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Answer extends Model
{
    protected $fillable = [

        'answer',
        'user_id',
        'ticket_id'
    ];


    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
