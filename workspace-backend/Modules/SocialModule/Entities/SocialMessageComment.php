<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageComment extends Model
{

    const slug          = 'comment_slug';
    const description   = 'comment_description';
    const parent_social_comment_id     = 'parent_social_comment_id';
    const social_message_id            = 'social_message_id';
    const user_id  = 'user_id';
    
    const table       = 'social_message_comment';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialMessageComment::table;

    protected $fillable = [
        SocialMessageComment::slug,
        SocialMessageComment::description,
        SocialMessageComment::parent_social_comment_id,
        SocialMessageComment::social_message_id,
        SocialMessageComment::user_id
        ];
}
