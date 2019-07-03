<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageDepartmentMap extends Model
{
    const department_id       = 'department_id';
    const social_message_id   = 'social_message_id';


    const table       = 'social_message_department_map';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialMessageDepartmentMap::table;

    protected $fillable = [
        SocialMessageDepartmentMap::department_id,
        SocialMessageDepartmentMap::social_message_id
    ];
}
