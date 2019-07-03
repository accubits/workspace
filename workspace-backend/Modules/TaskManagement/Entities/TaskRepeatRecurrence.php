<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskRepeatRecurrence extends Model
{
    const task_repeat_id    = 'task_repeat_id';
    const task_id           = 'task_id';
    const task_status_id    = 'task_status_id';
    const start_date        = 'start_date';
    const end_date          = 'end_date';
    const task_repeat_date  = 'task_repeat_date';

    const table = 'tm_task_repeat_recurrence';

    protected $table    = TaskRepeatRecurrence::table;

    protected $fillable = [

    ];
}
