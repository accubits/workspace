<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialEventCommentResponse extends Model
{

    const slug = 'slug';
    const event_comment_id = 'event_comment_id';
    const org_id = 'org_id';
    const user_id = 'user_id';
    const response_type_id  = 'response_type_id';


    const table       = 'social_event_comment_responses';
    protected $table = SocialEventCommentResponse::table;

    protected $fillable = [
        SocialEventCommentResponse::slug,
        SocialEventCommentResponse::org_id,
        SocialEventCommentResponse::event_comment_id,
        SocialEventCommentResponse::user_id,
        SocialEventCommentResponse::response_type_id
        ];

}
