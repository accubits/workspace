<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncementComment extends Model
{
    const slug                               = 'anc_comment_slug';
    const description                        = 'anc_comment_description';
    const parent_announcement_comment_id     = 'parent_announcement_comment_id';
    const social_announcement_id            = 'social_announcement_id';
    const user_id                            = 'user_id';
    const table                              = 'social_announcement_comment';

    protected $table = SocialAnnouncementComment::table;
    protected $fillable = [
            SocialAnnouncementComment::slug,
            SocialAnnouncementComment:: description,
            SocialAnnouncementComment:: parent_announcement_comment_id,
            SocialAnnouncementComment:: social_announcement_id ,
            SocialAnnouncementComment::user_id,

            ];
}
