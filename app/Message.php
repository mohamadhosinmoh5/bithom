<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'ticket_id',
        'message'
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
