<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProjectConfig extends Model
{
    protected $fillable = [
        'tax_percentage',
        'fee_percentage'
    ];

}
