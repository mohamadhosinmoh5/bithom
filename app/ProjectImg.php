<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectImg extends Model
{
    protected $fillable = [
        'project_id',
        'project_img_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
