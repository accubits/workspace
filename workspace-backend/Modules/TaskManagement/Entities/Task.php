<?php

namespace Modules\TaskManagement\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Common\Utilities\Utilities;

class Task extends Model
{
    const slug         = 'slug';
    const title        = 'title';
    const description  = 'description';
    const responsible_person_id     = 'responsible_person_id';
    const creator_user_id           = 'creator_user_id';
    const approver_user_id          = 'approver_user_id';
    const task_completed_user_id    = 'task_completed_user_id';
    const org_id       = 'org_id';
    const start_date   = 'start_date';
    const reminder     = 'reminder_date';
    const end_date     = 'end_date';
    const repeat       = 'repeat';
    const task_status_id     = 'task_status_id';
    const parent_task_id     = 'parent_task_id';
    const task_score_id      = 'task_score_id';
    const is_template        = 'is_template';
    const is_to_allemployees = 'is_to_allemployees';
    const is_reminder_sent   = 'is_reminder_sent';
    const responsible_person_time_change = 'responsible_person_time_change';
    const approve_task_completed         = 'approve_task_completed';
    const priority           = 'priority';
    const archive            = 'archive';
    const favourite          = 'favourite';
    const repeatCronStatus   = 'repeat_cron_status';

    const cronCompleted      = 'completed';

    const table = 'tm_tasks';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Task::table;

    protected $fillable = [
        Task::slug,
        Task::title,
        Task::description,
        Task::start_date,
        Task::end_date,
        Task::repeat,
        Task::parent_task_id,
        Task::is_reminder_sent,
        Task::archive,
        Task::priority,
        Task::favourite
    ];

    public function setStartDateAttribute($value)
    {
        $this->attributes[Task::start_date] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes[Task::end_date] = Utilities::createDateTimeFromUtc($value);
    }

    public function setReminderDateAttribute($value)
    {
        $this->attributes[Task::reminder] = ($value) ? Utilities::createDateTimeFromUtc($value) : NULL;
    }

    public function repeatTypeWeekly()
    {
        return $this->hasOne(TaskRepeatTypeWeekly::class);
    }


}
