<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class TaskStatusLog extends Model
{
    const task_id        = 'task_id';
    const user_id        = 'user_id';

    const previous_status_id  = 'task_previous_status_id';
    const current_status_id   = 'task_current_status_id';
    const status_log_time     = 'task_status_log_time';

    const table = 'tm_task_status_log';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskStatusLog::table;

    protected $fillable = [
        TaskStatusLog::task_id,
        TaskStatusLog::user_id,
        TaskStatusLog::previous_status_id,
        TaskStatusLog::current_status_id,
        TaskStatusLog::status_log_time,
    ];

}
