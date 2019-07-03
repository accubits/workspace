<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollResponse extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const pollgroup_id = 'pollgroup_id';
    const user_id = 'user_id';
    const response_type_id = 'response_type_id';
    
    const table       = 'social_poll_responses';

    protected $table = SocialPollResponse::table;
    protected $fillable = [
        SocialPollResponse::slug,
        SocialPollResponse::org_id,
        SocialPollResponse::pollgroup_id,
        SocialPollResponse::user_id,
        SocialPollResponse::response_type_id
        ];
}
