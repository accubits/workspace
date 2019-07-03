<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEventMember extends Model
{
    const user_id                          = 'user_id';
    const  social_event_id                 = 'social_event_id';
    const  response_status                 = 'response_status';

    const table                            = 'social_event_member';
    
    protected $table = SocialEventMember::table;

    protected $fillable = [
        SocialEventMember::response_status,
        SocialEventMember::user_id,
        SocialEventMember::social_event_id
    ];
}
