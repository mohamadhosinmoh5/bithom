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

}
