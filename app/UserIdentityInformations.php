<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class UserIdentityInformations extends Model
{
    protected $fillable = [
        'video_file_id',
        'nationalCard_file_id',
        'profile_file_id',

        'video_file_status',
        'nationalCard_file_status',
        'profile_file_status',

        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public const ACCEPTED = "1";
    public const FAILED = "0";
    public const AWAITING_CONFIRMATION = "2";
    public const NOT_TAKEN = "3";








}
