<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciationComment extends Model
{

    const slug          = 'slug';
    const description   = 'description';
    const parent_social_comment_id     = 'parent_apr_comment_id';
    const social_appreciation_id            = 'social_appreciation_id';
    const user_id  = 'user_id';
    
    const table       = 'social_appreciation_comments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialAppreciationComment::table;

    protected $fillable = [
        SocialAppreciationComment::slug,
        SocialAppreciationComment::description,
        SocialAppreciationComment::parent_social_comment_id,
        SocialAppreciationComment::social_appreciation_id,
        SocialAppreciationComment::user_id
        ];
}
