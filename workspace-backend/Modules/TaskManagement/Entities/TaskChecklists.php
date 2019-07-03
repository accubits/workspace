<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskChecklists extends Model
{
    const slug         = 'slug';
    const description  = 'description';
    const task_id      = 'task_id';
    const user_id      = 'user_id';
    const checklist_status    = 'checklist_status';

    const CHECKLIST_COMPLETED_STATUS  = TRUE;
    const CHECKLIST_INPROGRESS_STATUS = FALSE;

    const table = 'tm_task_checklists';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskChecklists::table;

    protected $fillable = [
        TaskChecklists::slug,
        TaskChecklists::description,
        TaskChecklists::task_id,
        TaskChecklists::user_id,
        TaskChecklists::checklist_status
    ];
}
