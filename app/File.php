<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class File extends Model
{
    protected $fillable = [
        'id',
        'url',
        'model',
        'type_id',
        'type_file',
        'mime_type'
    ];

    const VIDEOO = "video";
    const IMG = "image";
    const ATTECHMENT = "attechment";

    const USER = "user";
    const PROJECT = "project";

    public function projectImg()
    {
        return $this->hasOne(Project::class, 'main_img_id', 'id');
    }

    public function companyImg()
    {
        return $this->hasOne(Company::class, 'logo_file_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'type_id', 'id');
    }




    public function identityInformation()
    {
        return $this->hasOne(UserIdentityInformations::class, 'nationalCard_file_id', 'id');
    }


}
