<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialMessageUserMap extends Model
{
    const user_id       = 'user_id';
    const social_message_id   = 'social_message_id';
    const read_status   = 'read_status';
    const read_datetime = 'read_datetime';

    const table       = 'social_message_user_map';

    protected $table = SocialMessageUserMap::table;

    protected $fillable = [
        SocialMessageUserMap::user_id,
        SocialMessageUserMap::social_message_id,
        SocialMessageUserMap::read_status,
        SocialMessageUserMap::read_datetime,
    ];
}
