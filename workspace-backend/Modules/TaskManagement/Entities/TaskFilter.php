<?php

namespace Modules\TaskManagement\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class TaskFilter extends Model
{
    const title              = 'filter_title';
    const slug               = 'filter_slug';
    const user_id            = 'user_id';
    const org_id             = 'org_id';
    const priority           = 'priority';
    const favourite          = 'favourite';
    const is_attachment      = 'is_attachment';
    const is_subtask         = 'is_subtask';
    const is_checklist       = 'is_checklist';
    const due_date           = 'due_date';
    const start_date         = 'start_date';
    const finished_on        = 'finished_on';
    const participant        = 'participant';
    const responsible_person = 'responsible_person';
    const task_status        = 'task_status';
    const created_by         = 'created_by';


    const table = 'tm_task_filter';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFilter::table;


    protected $fillable = [];

    public function setDueDateAttribute($value)
    {
        $this->attributes[TaskFilter::due_date] = Utilities::createDateTimeFromUtc($value);
    }

    public function getDueDateAttribute($value)
    {
        return ($value)? Carbon::parse($value)->timestamp : NULL;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes[TaskFilter::start_date] = Utilities::createDateTimeFromUtc($value);
    }

    public function getStartDateAttribute($value)
    {
        return ($value)? Carbon::parse($value)->timestamp : NULL;
    }

    public function setFinishedOnAttribute($value)
    {
        $this->attributes[TaskFilter::finished_on] = Utilities::createDateTimeFromUtc($value);
    }

    public function getFinishedOnAttribute($value)
    {
        return ($value)? Carbon::parse($value)->timestamp : NULL;
    }
}
