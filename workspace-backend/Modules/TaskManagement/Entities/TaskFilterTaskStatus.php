<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskFilterTaskStatus extends Model
{
    const task_status_id = 'task_status_id';
    const task_filter_id        = 'task_filter_id';


    const table = 'tm_task_filter_task_status';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFilterTaskStatus::table;


    protected $fillable = [
        TaskFilterTaskStatus::task_filter_id,
        TaskFilterTaskStatus::task_status_id
    ];
}
