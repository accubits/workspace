<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialPollComment extends Model
{
    const slug          = 'comment_slug';
    const description   = 'comment_description';
    const parent_social_comment_id     = 'parent_social_comment_id';
    const social_pollgroup_id            = 'social_pollgroup_id';
    const user_id  = 'user_id';
    
    const table       = 'social_poll_comments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialPollComment::table;

    protected $fillable = [
        SocialPollComment::slug,
        SocialPollComment::description,
        SocialPollComment::parent_social_comment_id,
        SocialPollComment::social_pollgroup_id,
        SocialPollComment::user_id
        ];
}
