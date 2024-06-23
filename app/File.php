<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    protected $fillable = [
        'type',
        'file',
    ];


}
