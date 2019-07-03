<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciation extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const title  = 'title';
    const description = 'description';
    const creator_user_id = 'creator_user_id';
    const notify_appreciation_to_all = 'notify_appreciation_to_all';
    const has_duration = 'has_duration';
    const duration_start = 'duration_start';
    const duration_end = 'duration_end';
    const status_id = 'status_id';

    const table = 'social_appreciations';

    protected $table     =  SocialAppreciation::table;

    protected $fillable = [SocialAppreciation::org_id, 
                           SocialAppreciation::description,
                           SocialAppreciation::title,
                           SocialAppreciation::status_id
                           ];
}
