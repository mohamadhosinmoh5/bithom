<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    protected $fillable = [
        'first_img',
        'video',
        'image',
        'project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

}
