<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamMessageMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const message_id = 'message_id';

    const table = 'social_activity_stream_message';

    protected $table = SocialActivityStreamMessageMap::table;
    protected $fillable = [
        SocialActivityStreamMessageMap::activity_stream_master_id,
        SocialActivityStreamMessageMap::message_id
    ];
}
