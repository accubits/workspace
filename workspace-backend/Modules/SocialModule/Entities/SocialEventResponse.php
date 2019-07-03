<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEventResponse extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const event_id = 'event_id';
    const user_id = 'user_id';
    const response_type_id = 'response_type_id';
    
    const table       = 'social_event_responses';

    protected $table = SocialEventResponse::table;
    protected $fillable = [
        SocialEventResponse::slug,
        SocialEventResponse::org_id,
        SocialEventResponse::event_id,
        SocialEventResponse::user_id,
        SocialEventResponse::response_type_id
        ];
}
