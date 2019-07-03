<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEventDepartment extends Model
{
    const department_id                    = 'department_id';
    const  social_event_id                 = ' social_event_id';
    const table                            = 'social_event_department';
    
    protected $table = SocialEventDepartment::table;

    protected $fillable = [
        SocialEventDepartment::department_id,
        SocialEventDepartment::social_event_id
    ];
}
