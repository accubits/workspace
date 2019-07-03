<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskParticipants extends Model
{
    const slug          =   'slug';
    const task_id       =   'task_id';
    const user_id       =   'user_id';

    const table = 'tm_task_participants';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskParticipants::table;

    protected $fillable = [
        TaskParticipants::slug,
        TaskParticipants::task_id,
        TaskParticipants::user_id
    ];
}
