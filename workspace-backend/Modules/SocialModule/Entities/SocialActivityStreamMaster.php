<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamMaster extends Model
{
    const org_id = 'org_id';
    const activity_stream_type_id = 'activity_stream_type_id';
    const stream_datetime = 'stream_datetime';
    const note = 'note';
    const from_user_id = 'from_user_id';
    const is_hidden = 'is_hidden'; 

    const table = 'social_activity_stream_master';

    protected $table = SocialActivityStreamMaster::table;
    protected $fillable = [
        SocialActivityStreamMaster::org_id,
        SocialActivityStreamMaster::activity_stream_type_id,
        SocialActivityStreamMaster::stream_datetime,
        SocialActivityStreamMaster::from_user_id
        ];
}
