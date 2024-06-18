<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Unit extends Model
{
    protected $fillable = [
        'idProject',
        'remainingMeterage',
        'idProjectType',
        'store',
        'parking',
        'room',
        'meterege',
        'price',
        'title'
    ];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'idProjectType', 'id');
    }
    public function Project()
    {
        return $this->belongsTo(Project::class, 'idProject', 'id');
    }
}
