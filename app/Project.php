<?php

namespace App;

use App\Models\User;
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
        'start_price_investment',
        'supply_status_code',
        'baseTitle',
        'currentPrice',
        'main_img_id',
        'user_id'
    ];


    const PRESENTING = "0";
    const AWAITING_ESTOCKING = "1";
    const FINISHED = "2";


    public function file()
    {
        return $this->hasMany(File::class, 'type_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mainImg()
    {
        return $this->belongsTo(File::class, 'main_img_id', 'id');
    }






    public function product()
    {
        return $this->hasMany(Product::class, 'project_id', 'id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'project_id', 'id');
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'projectType_id', 'id');
    }

    public function unit()
    {
        return $this->hasMany(Unit::class, 'project_id', 'id');
    }

    public function projectConfig()
    {
        return $this->hasOne(projectConfig::class, 'project_id', 'id');
    }


}
