<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskRepeatMap extends Model
{
    const task_id         = 'task_id';
    const origin_task_id  = 'origin_task_id';

    const table = 'tm_task_repeat_map';

    protected $table    = TaskRepeatMap::table;

    protected $fillable = [
        TaskRepeatMap::task_id,
        TaskRepeatMap::origin_task_id
    ];
}
