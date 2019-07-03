<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamAnnouncementMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const annoucement_id = 'annoucement_id';

    const table = 'social_activity_stream_announcement';

    protected $table = SocialActivityStreamAnnouncementMap::table;
    protected $fillable = [
        SocialActivityStreamAnnouncementMap::activity_stream_master_id,
        SocialActivityStreamAnnouncementMap::annoucement_id
    ];
}
