<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageResponse extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const message_id = 'message_id';
    const user_id = 'user_id';
    const response_type_id = 'response_type_id';
    
    const table       = 'social_message_responses';

    protected $table = SocialMessageResponse::table;
    protected $fillable = [
        SocialMessageResponse::slug,
        SocialMessageResponse::org_id,
        SocialMessageResponse::message_id,
        SocialMessageResponse::user_id,
        SocialMessageResponse::response_type_id
        ];
}
