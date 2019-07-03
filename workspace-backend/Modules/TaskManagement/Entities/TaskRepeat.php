<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class TaskRepeat extends Model
{
    const repeat_every    = 'repeat_every';
    const task_id         = 'task_id';
    const user_id         = 'user_id';
    const task_repeat_type_id      = 'task_repeat_type_id';
    const ends_never      = 'ends_never';
    const ends_on         = 'ends_on';
    const ends_after      = 'ends_after';

    const table = 'tm_task_repeat';

    protected $table    = TaskRepeat::table;

    protected $fillable = [
        TaskRepeat::repeat_every,
        TaskRepeat::task_id,
        TaskRepeat::user_id,
        TaskRepeat::task_repeat_type_id,
        TaskRepeat::ends_on
    ];

    public function setEndsOnAttribute($value)
    {
        $this->attributes[TaskRepeat::ends_on] = ($value)? Utilities::createDateTimeFromUtc($value) : NULL;
    }

    public function taskRepeatType()
    {
        return $this->belongsTo(TaskRepeatType::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
