<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncementDepartmentMap extends Model
{
    const department_id                    = 'department_id';
    const social_announcement_id           = ' social_announcement_id';
    const table                            = 'social_announcement_department_map';
    
    protected $table = SocialAnnouncementDepartmentMap::table;

    protected $fillable = [
                            SocialAnnouncementDepartmentMap::department_id,
                            SocialAnnouncementDepartmentMap::social_announcement_id 
                         ];
}
