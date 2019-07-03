<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialActivityStreamTaskMap extends Model
{
    const activity_stream_master_id = 'activity_sm_id';
    const task_id = 'task_id';

    const table = 'social_activity_stream_task';

    protected $table = SocialActivityStreamTaskMap::table;
    protected $fillable = [
        SocialActivityStreamTaskMap::activity_stream_master_id,
        SocialActivityStreamTaskMap::task_id
        ];
}
