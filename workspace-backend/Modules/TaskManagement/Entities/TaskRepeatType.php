<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskRepeatType extends Model
{
    const title         = 'title';

    const table = 'tm_task_repeat_type';

    const DAY    = 'day';
    const WEEK   = 'week';
    const MONTH  = 'month';
    const YEAR   = 'year';

    protected $table = TaskRepeatType::table;

    protected $fillable = [
        TaskRepeatType::title
    ];
}
