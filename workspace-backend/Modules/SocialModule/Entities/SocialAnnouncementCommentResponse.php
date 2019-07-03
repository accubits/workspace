<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncementCommentResponse extends Model
{
    const slug                               = 'slug';
    const anc_comment_id   = 'anc_comment_id';
    const org_id     = 'org_id';
    const user_id            = 'user_id';
    const response_type_id  = 'response_type_id';


    const table       = 'social_announcement_comment_responses';
    protected $table = SocialAnnouncementCommentResponse::table;

    protected $fillable = [
        SocialAnnouncementCommentResponse::slug,
        SocialAnnouncementCommentResponse::org_id,
        SocialAnnouncementCommentResponse::anc_comment_id,
        SocialAnnouncementCommentResponse::user_id,
        SocialAnnouncementCommentResponse::response_type_id
        ];
}
