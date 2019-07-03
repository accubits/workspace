<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskFilterResponsiblePersons extends Model
{
    const responsible_person_id = 'responsible_person_id';
    const task_filter_id        = 'task_filter_id';


    const table = 'tm_task_filter_responsible_persons';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFilterResponsiblePersons::table;


    protected $fillable = [
        TaskFilterResponsiblePersons::responsible_person_id,
        TaskFilterResponsiblePersons::task_filter_id
    ];
}
