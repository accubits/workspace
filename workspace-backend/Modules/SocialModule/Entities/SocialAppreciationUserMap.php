<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialAppreciationUserMap extends Model
{
    const user_id         = 'user_id';
    const appreciation_id = 'appreciation_id';
    const mark_as_read = 'mark_as_read';
    const read_datetime = 'read_datetime';

    const table = 'social_appreciation_user_maps';
    
    protected $table = SocialAppreciationUserMap::table;

    protected $fillable = [
        SocialAppreciationUserMap::mark_as_read,
        SocialAppreciationUserMap::user_id,
        SocialAppreciationUserMap::appreciation_id
    ];
}
