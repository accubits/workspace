<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciationCommentResponse extends Model
{
    const slug = "slug";
    const appreciation_comment_id   = 'appreciation_comment_id';
    const org_id     = 'org_id';
    const user_id            = 'user_id';
    const response_type_id  = 'response_type_id';


    const table       = 'social_appreciation_comment_responses';
    protected $table = SocialAppreciationCommentResponse::table;

    protected $fillable = [
        SocialAppreciationCommentResponse::slug,
        SocialAppreciationCommentResponse::org_id,
        SocialAppreciationCommentResponse::appreciation_comment_id,
        SocialAppreciationCommentResponse::user_id,
        SocialAppreciationCommentResponse::response_type_id
        ];
}
