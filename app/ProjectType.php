<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectType extends Model
{
    protected $fillable = [
        'type'
    ];

    public function product()
    {
        return $this->hasMany(Project::class, 'projectType_id', 'id');
    }

    public function unit()
    {
        return $this->hasMany(Unit::class, 'projectType_id', 'id');
    }



}
