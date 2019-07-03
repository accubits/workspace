<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskFilterParticipants extends Model
{
    const participant_id     = 'participant_id';
    const task_filter_id     = 'task_filter_id';


    const table = 'tm_task_filter_participants';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFilterParticipants::table;


    protected $fillable = [
        TaskFilterParticipants::participant_id,
        TaskFilterParticipants::task_filter_id
    ];
}
