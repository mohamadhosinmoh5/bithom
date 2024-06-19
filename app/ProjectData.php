<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectData extends Model
{
    protected $fillable = [
        'project_id',
        'file',
        'title',
        'type'
    ];
}
