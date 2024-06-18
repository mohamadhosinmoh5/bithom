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
}
