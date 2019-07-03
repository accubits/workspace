<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamEventMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const event_id = 'event_id';

    const table = 'social_activity_stream_event_map';

    protected $table = SocialActivityStreamEventMap::table;
    protected $fillable = [
        SocialActivityStreamEventMap::activity_stream_master_id,
        SocialActivityStreamEventMap::event_id
    ];
}
