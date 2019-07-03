<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageCommentResponse extends Model
{
    
    const slug          = 'slug';
    const message_comment_id   = 'message_comment_id';
    const org_id     = 'org_id';
    const user_id            = 'user_id';
    const response_type_id  = 'response_type_id';


    const table       = 'social_message_comment_responses';
    protected $table = SocialMessageCommentResponse::table;

    protected $fillable = [
        SocialMessageCommentResponse::slug,
        SocialMessageCommentResponse::org_id,
        SocialMessageCommentResponse::message_comment_id,
        SocialMessageCommentResponse::user_id,
        SocialMessageCommentResponse::response_type_id
        ];
}
