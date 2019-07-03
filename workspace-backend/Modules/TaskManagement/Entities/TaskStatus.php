<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    const slug         = 'slug';
    const title        = 'title';
    const display_name = 'display_name';

    const active       = 'active';
    const overdue      = 'overdue';
    const ongoing      = 'ongoing';
    const pause        = 'pause';
    const completed_waiting_approval = 'completed_waiting_approval';
    const completed_approved         = 'completed_approved';

    const table = 'tm_task_status';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskStatus::table;

    protected $fillable = [
        TaskStatus::title,
        TaskStatus::display_name
    ];
}
