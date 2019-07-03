<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncement extends Model
{
    const slug         = 'announcement_slug';
    const title                  = 'announcement_title';
    const description            = 'announcement_description';
    const org_id                 = 'org_id';
    const creator_user_id        = 'creator_user_id';
    const is_announcement_to_all = 'is_announcement_to_all';
    const table                  = 'social_announcement';

    protected $table = SocialAnnouncement::table;

    protected $fillable = [SocialAnnouncement::title, 
                          SocialAnnouncement::description,
                          SocialAnnouncement::org_id,
                          SocialAnnouncement::creator_user_id
                         ];
}
