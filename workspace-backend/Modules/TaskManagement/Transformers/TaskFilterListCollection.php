<?php

namespace Modules\TaskManagement\Transformers;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\TaskManagement\Entities\TaskFilterParticipants;
use Modules\UserManagement\Entities\User;

class TaskFilterListCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'filterSlug'        => $this->{TaskFilter::slug},
            'filterName'        => $this->{TaskFilter::title},
            'taskStatus'      => $this->task_filter_status,
            'priority'    => (boolean) $this->{TaskFilter::priority},
            'favourite'       => (boolean) $this->{TaskFilter::favourite},
            'withAttachement'       => (boolean) $this->{TaskFilter::is_attachment},
            'includesSubtask'       => (boolean) $this->{TaskFilter::is_subtask},
            'includesChecklist'     => (boolean) $this->{TaskFilter::is_checklist},
            'dueDate'       => $this->{TaskFilter::due_date},
            'startDate'     => $this->{TaskFilter::start_date},
            'finishedOn'    => $this->{TaskFilter::finished_on},
            'participants' => $this->participants,
            'createdBy' => $this->created_users,
            'responsiblePerson' => $this->responsible_persons
        ];
    }
}
