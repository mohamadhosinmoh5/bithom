<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Media extends Model
{
    protected $fillable = [
        'first_img',
        'video',
        'image',
        'project_id'
    ];
}
