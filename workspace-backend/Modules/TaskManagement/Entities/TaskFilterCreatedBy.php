<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskFilterCreatedBy extends Model
{
    const created_by_id     = 'created_by_id';
    const task_filter_id     = 'task_filter_id';


    const table = 'tm_task_filter_created_by';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFilterCreatedBy::table;


    protected $fillable = [
        TaskFilterCreatedBy::created_by_id,
        TaskFilterCreatedBy::task_filter_id
    ];
}
