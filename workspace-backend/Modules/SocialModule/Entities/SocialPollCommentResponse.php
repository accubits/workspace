<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollCommentResponse extends Model
{
    const slug = 'slug';
    const pollgroup_comment_id   = 'pollgroup_comment_id';
    const org_id     = 'org_id';
    const user_id    = 'user_id';
    const response_type_id  = 'response_type_id';


    const table = 'social_poll_comment_responses';
    protected $table = SocialPollCommentResponse::table;

    protected $fillable = [
        SocialPollCommentResponse::slug,
        SocialPollCommentResponse::org_id,
        SocialPollCommentResponse::pollgroup_comment_id,
        SocialPollCommentResponse::user_id,
        SocialPollCommentResponse::response_type_id
        ];
}
