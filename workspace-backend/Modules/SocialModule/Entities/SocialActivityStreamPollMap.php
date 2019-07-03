<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamPollMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const poll_group_id = 'poll_group_id';

    const table = 'social_activity_stream_poll_map';

    protected $table = SocialActivityStreamPollMap::table;
    protected $fillable = [
        SocialActivityStreamPollMap::activity_stream_master_id,
        SocialActivityStreamPollMap::poll_group_id
        ];
}
