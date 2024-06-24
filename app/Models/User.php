<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Answer;
use App\File;
use App\Message;
use App\Product;
use App\Ticket;
use App\UserIdentityInformation;
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
        'auth_status',
        'address',
        'province',
        'city',
        'componyCode',
        'inviteCode',
        'remember_token'

    ];


    public function identityInformation()
    {
        return $this->belongsTo(UserIdentityInformation::class, 'user_id', 'id');
    }

    public function answer()
    {
        return $this->hasMany(Answer::class, 'user_id', 'id');
    }


    public function message()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'user_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
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
