<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Answer;
use App\Message;
use App\Product;
use App\Ticket;
use App\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'otp',
        'family',
        'birthdate',
        'nationalCode',
        'nationalCardImg',
        'video',
        'profile',
        'authStatus',
        'address',
        'province',
        'city',
        'componyCode',
        'inviteCode'

    ];

    public function answer()
    {
        return $this->hasMany(Answer::class, 'idTicket', 'id');
    }


    public function message()
    {
        return $this->hasMany(Message::class, 'idTicket', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'idUser', 'id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'idUser', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'idUser', 'id');
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
