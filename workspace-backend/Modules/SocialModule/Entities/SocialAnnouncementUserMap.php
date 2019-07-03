<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAnnouncementUserMap extends Model
{
    const user_id                          = 'user_id';
    const social_announcement_id           = 'social_announcement_id';
    const mark_as_read                     = 'mark_as_read';
    const read_datetime = 'read_datetime';
    const table                            = 'social_announcement_user_map';
    
    protected $table = SocialAnnouncementUserMap::table;

    protected $fillable = [
                            SocialAnnouncementUserMap::user_id,
                            SocialAnnouncementUserMap::social_announcement_id,
                            SocialAnnouncementUserMap::mark_as_read,
                            SocialAnnouncementUserMap::read_datetime
                        ];
}
