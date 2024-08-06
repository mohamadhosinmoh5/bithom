<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    protected $fillable = [
        'id',
        'comany_name',
        'company_type',
        'registration_num',
        'registration_date',
        'registration_city',
        'logo',
        'company_email',
        'phone',
        'postal_code',
        'local_city',
        'national_code',
        'agent_name',
        'agent_family',
        'user_id',
        'company_address'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
