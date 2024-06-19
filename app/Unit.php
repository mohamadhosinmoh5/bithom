<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Unit extends Model
{
    protected $fillable = [
        'project_id',
        'remaining_meterage',
        'projectType_id',
        'store',
        'parking',
        'room',
        'meterege',
        'price',
        'title'
    ];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'projectType_id', 'id');
    }
    public function Project()
    {
        return $this->belongsTo(Project::class, 'projectType_id', 'id');
    }
}
