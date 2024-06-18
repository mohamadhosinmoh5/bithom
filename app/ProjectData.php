<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectData extends Model
{
    protected $fillable = [
        'idProject',
        'file',
        'title',
        'type'
    ];
}
