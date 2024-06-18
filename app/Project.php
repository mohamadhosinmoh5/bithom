<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    protected $fillable = [
        'supplyStatus',
        'title',
        'city',
        'maker',
        'startTime',
        'endTime',
        'price',
        'address',
        'lat',
        'long',
        'area',
        'idProjectType',
        'meterage',
        'floor',
        'unit',
        'basePrice',
        'projectInfo',
        'investmentStatus',
        'remainingArea',
        'startPrice'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'idProject', 'id');
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'idProjectType', 'id');
    }

    public function unit()
    {
        return $this->hasMany(Unit::class, 'idProject', 'id');
    }


}
