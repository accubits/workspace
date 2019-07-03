<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamAppreciationMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const appreciation_id = 'appreciation_id';

    const table = 'social_activity_stream_appreciation_map';

    protected $table = SocialActivityStreamAppreciationMap::table;
    protected $fillable = [
        SocialActivityStreamAppreciationMap::activity_stream_master_id,
        SocialActivityStreamAppreciationMap::appreciation_id
    ];
}
