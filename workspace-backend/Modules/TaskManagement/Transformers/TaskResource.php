<?php

namespace Modules\TaskManagement\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Carbon;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\Task;

class TaskResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        //return parent::toArray($request);
        return [
            'slug'    => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'startDate' => $this->when($this->start_date, Carbon::parse($this->start_date)->timestamp, ""),
            'endDate' => Carbon::parse($this->end_date)->timestamp,
            'responsiblePerson' => [
                'responsiblePersonName' => $this->responsible_person,
                'responsiblePersonId' => $this->responsible_person_id
            ],

            'creator' => [
                'creatorName' => $this->creator,
                'creatorSlug' => $this->creator_slug
            ],
            'approver' => [
                'approverName' => $this->approver,
                'approverSlug' => $this->approver_slug
            ],
            'responsiblePersonCanChangeTime' => (bool) $this->responsible_person_time_change,
            'approveTaskCompleted' => (bool) $this->approve_task_completed,
            'priority' => (bool) $this->priority,
            'favourite' => (bool) $this->favourite,
            'isAllParticipants' => (bool) $this->isAllParticipants,
            'assignees' => $this->when(!empty($this->assignees), function () {
                return collect($this->assignees)->map(function ($assignee) {
                    return [
                        'assigneeName' => $assignee->assignee_name,
                        'assigneeSlug' => $assignee->participant_id
                    ];
                });
            },[]),
            'checklists' => $this->when(!empty($this->checklists), function () {
                return collect($this->checklists)->map(function ($checklist) {
                    return [
                        'slug' => $checklist->slug,
                        'description' => $checklist->description,
                        'checklistStatus' => (bool) $checklist->checklist_status
                    ];
                });
            },[]),
            'existingFiles' => $this->when(!empty($this->files), function () {
                $s3FilePath = env('S3_PATH');
                $orgSlug    = Organization::select(Organization::slug)
                    ->where(Organization::table. '.id', $this->org_id)->first();
                $attachmentPath = "$s3FilePath{$orgSlug->org_slug}/task/{$this->slug}/";

                return $this->files->map(function ($file) use ($attachmentPath) {
                    return [
                        'fileSlug' => $file->taskfile_slug,
                        'name' => $file->filename,
                        'size' => $file->size,
                        'filePath' => $attachmentPath. $file->filename,
                        'taskSlug' => $this->slug
                    ];
                });
            },[]),
            'parentTask' => $this->when($this->parent_task_id, function () {
                $task = Task::select(Task::slug, Task::title)->where(Task::table. '.id', $this->parent_task_id)->first();
                return [
                    'parentTaskSlug'  => $task->{Task::slug},
                    'parentTaskTitle' => $task->{Task::title}
                ];
            },""),
            'reminder' => $this->when($this->reminder_date, Carbon::parse($this->reminder_date)->timestamp, ""),
            'repeat' => $this->when(!empty($this->repeatable), function () {
                return [
                    'repeatType' => $this->repeatable['repeat_type'],
                    'repeatEvery' => $this->repeatable['repeat_every'],
                    'week' => $this->when($this->repeatable['repeat_type'] == 'week', function () {

                        return [
                            'Sunday' => (bool) $this->repeatable['week']->sun,
                            'Monday' => (bool) $this->repeatable['week']->mon,
                            'Tuesday' => (bool) $this->repeatable['week']->tue,
                            'Wednesday' => (bool) $this->repeatable['week']->wed,
                            'Thursday' => (bool) $this->repeatable['week']->thu,
                            'Friday' => (bool) $this->repeatable['week']->fri,
                            'Saturday' => (bool) $this->repeatable['week']->sat,
                        ];
                    }),
                    'ends' => $this->repeatable['ends']
                ];
            }, new \stdClass()),
        ];
    }
}
