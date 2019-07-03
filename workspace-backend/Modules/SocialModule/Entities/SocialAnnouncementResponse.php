<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncementResponse extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const annoucement_id = 'annoucement_id';
    const user_id = 'user_id';
    const response_type_id = 'response_type_id';
    
    const table       = 'social_announcement_responses';

    protected $table = SocialAnnouncementResponse::table;
    protected $fillable = [
        SocialAnnouncementResponse::slug,
        SocialAnnouncementResponse::org_id,
        SocialAnnouncementResponse::annoucement_id,
        SocialAnnouncementResponse::user_id,
        SocialAnnouncementResponse::response_type_id
        ];
}
