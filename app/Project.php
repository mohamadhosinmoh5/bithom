<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    protected $fillable = [
        'supply_status',
        'title',
        'city',
        'maker',
        'start_time',
        'end_time',
        'price',
        'address',
        'lat',
        'long',
        'area',
        'projectType_id',
        'meterage',
        'floor',
        'unit',
        'base_price',
        'project_info',
        'investment_status',
        'remaining_area',
        'start_price'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'project_id', 'id');
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'projectType_id', 'id');
    }

    public function unit()
    {
        return $this->hasMany(Unit::class, 'project_id', 'id');
    }

    public function file()
    {
        return $this->hasMany(File::class, 'project_id', 'id');
    }

    public function projectFile()
    {
        return $this->hasMany(ProjectFile::class, 'project_id', 'id');
    }

}
