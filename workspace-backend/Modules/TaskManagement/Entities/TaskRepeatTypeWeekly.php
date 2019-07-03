<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskRepeatTypeWeekly extends Model
{
    const sunday    = 'sun';
    const monday    = 'mon';
    const tuesday   = 'tue';
    const wednesday = 'wed';
    const thursday  = 'thu';
    const friday    = 'fri';
    const saturday  = 'sat';
    const task_id  = 'task_id';

    const table = 'tm_task_repeat_type_weekly';

    protected $table    = TaskRepeatTypeWeekly::table;

    protected $fillable = [
        TaskRepeatTypeWeekly::sunday,
        TaskRepeatTypeWeekly::monday,
        TaskRepeatTypeWeekly::tuesday,
        TaskRepeatTypeWeekly::wednesday,
        TaskRepeatTypeWeekly::thursday,
        TaskRepeatTypeWeekly::friday,
        TaskRepeatTypeWeekly::saturday,
        TaskRepeatTypeWeekly::task_id
    ];
}
