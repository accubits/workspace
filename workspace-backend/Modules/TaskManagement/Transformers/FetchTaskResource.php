<?php

namespace Modules\TaskManagement\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\TaskFile;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\TaskRepeatType;
use Modules\TaskManagement\Entities\TaskStatus;

class FetchTaskResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $creatorUserId = $this->creator_user;
        $taskId        = $this->id;
        
        return [
            'slug'        => $this->slug,
            'taskStatus' => $this->task_status,
            'currentTaskStatus' => camel_case($this->task_status),
            'isEditable' => (bool) $this->isEditable,
            'priority'    => (boolean) $this->priority,
            'favourite'    => (boolean) $this->favourite,
            'responsibleCanChangeDueDate' => (boolean) $this->responsibleCanChangeDueDate,
            'createdOn'  => Carbon::parse($this->created_at)->timestamp,
            'startDate'  => $this->when($this->start_date, Carbon::parse($this->start_date)->timestamp, ""),
            'title'       => $this->title,
            'description' => $this->description,
            'dueDate'    => $this->when($this->end_date, Carbon::parse($this->end_date)->timestamp, ""),
            'reminder'    => $this->when($this->reminder_date, Carbon::parse($this->reminder_date)->timestamp, ""),
            'attachments' => $this->when($this->id, function () {
                $attachments = TaskFile::select(TaskFile::taskfile_slug, TaskFile::filename, TaskFile::filesize)
                    ->where(TaskFile::task_id, $this->id)->get();

                if ($attachments->isNotEmpty()) {
                    $s3FilePath = env('S3_PATH');
                    $orgSlug = Organization::select(Organization::slug)
                        ->where(Organization::table. '.id', $this->org_id)->first();
                    $attachmentPath = "$s3FilePath{$orgSlug->org_slug}/task/{$this->slug}/";

                    return $attachments->map(function ($attachment) use ($s3FilePath, $attachmentPath) {
                        return [
                            'taskFileName' => $attachment->{TaskFile::filename},
                            'taskFileSlug' => $attachment->{TaskFile::taskfile_slug},
                            'taskFilePath' => $attachmentPath.$attachment->{TaskFile::filename},
                            'taskFileSize' => $attachment->{TaskFile::filesize}
                        ];
                    });
                }

                return [];
            },[]),

            'estimateDays'    => $this->when($this->start_date, function () {
                /*$startDate = Carbon::parse($this->start_date);
                $endDate   = Carbon::parse($this->end_date);
                return $startDate->diffInDays($endDate);*/
                $startDate = $this->start_date;
                $endDate   = $this->end_date;
                return $this->dateDifference($startDate, $endDate);

            }, "1 Day"),
            'taskRepeat' => $this->when($this->repeat, function () use($creatorUserId, $taskId) {
                $repeatType = DB::table(TaskRepeat::table)->select(TaskRepeatType::table. '.' .TaskRepeatType::title)
                    ->join(TaskRepeatType::table, TaskRepeatType::table. '.id', '=', TaskRepeat::table. '.' .TaskRepeat::task_repeat_type_id)
                    ->where(TaskRepeat::table. '.' .TaskRepeat::task_id, $taskId)
                    ->where(TaskRepeat::table. '.' .TaskRepeat::user_id, $creatorUserId)
                    ->pluck(TaskRepeatType::title)
                    ->first();
                $repeatText = "";
                if ($repeatType == TaskRepeatType::WEEK) {
                    $repeatText = 'Weekly';
                } else if ($repeatType == TaskRepeatType::MONTH) {
                    $repeatText = 'Monthly';
                } else if ($repeatType == TaskRepeatType::DAY) {
                    $repeatText = 'Daily';
                } else if ($repeatType == TaskRepeatType::YEAR) {
                    $repeatText = 'Yearly';
                }
                return $repeatText;
            }, ""),
            'isApprover' => (boolean) !$this->isApprover,

            'creator'     => [
                'creatorName' => $this->creator,
                'creatorSlug' => $this->creator_slug,
                'creatorImage' => $this->creatorImage
            ],
            'responsiblePerson' => [
                'responsiblePersonName' => $this->responsible_person_name,
                'responsiblePersonSlug'   => $this->responsible_person_id,
                'responsiblePersonImage'  => $this->responsiblePersonImage
            ],
            'approver' => [
                'approverName' => $this->approverName,
                'approverSlug'   => $this->approverSlug,
                'approverImage'  => $this->approverImage
            ],
            'taskCompletedUser' => [
                'completedUserName'  => $this->completedUser,
                'completedUserImage' => $this->completedPersonImage
            ],
            'isAllParticipants' => (bool) $this->isAllParticipants,
            'participants' => $this->assignees,
            'checklists' => [
                'total'         => count($this->checklists),
                'totalChecked' => count($this->checklists->where('checklist_status', true)),
                'data'  => $this->checklists->map(function ($item) {
                    return [
                        'description'      => $item->description,
                        'checklistSlug'     => $item->slug,
                        'checklistStatus' => (boolean) $item->checklist_status
                    ];
                })
            ],

            'userStatusButtons' => $this->getUserButtons($this)


        ];
    }

    public function dateDifference($date_1 , $date_2)
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);
        $intervalFormat = 0;

        if ($interval->format('%y') == 0 && $interval->format('%m') == 0 &&
            $interval->format('%d') == 0 && $interval->format('%h') == 0) {
            $intervalFormat = $interval->format('%i Minute');
        } else if ($interval->format('%y') == 0 && $interval->format('%m') == 0 &&
            $interval->format('%d') == 0) {
            $intervalFormat = ($interval->format('%i') == 0) ? $interval->format('%h Hour') : $interval->format('%h Hour %i Minute');
        } else if ($interval->format('%y') == 0 && $interval->format('%m') == 0) {
            if ($interval->format('%h') == 0 && $interval->format('%i') == 0)
                $intervalFormat = $interval->format('%d Day');
            else
                $intervalFormat = $interval->format('%d Day %h Hour %i Minute');
        } else if ($interval->format('%y') == 0) {
                $intervalFormat = ($interval->format('%d') == 0)? $interval->format('%m Month') : $interval->format('%m Month %d Day');
        } else {
            if ($interval->format('%m') == 0)
               $intervalFormat = $interval->format('%y Year');
            else
               $intervalFormat = $interval->format('%y Year %m Month');
        }

        //dd($interval->format('%y'));

        return $intervalFormat;

    }

    public function getUserButtons()
    {
        $buttonArrays = ['start' => false, 'pause' => false, 'complete' => false, 'accepted' => false,
            'returnTask' => false
        ];

        $me = Auth::user();
        if (($this->creator_user == $me->id) && ($this->responsible_person == $me->id) && ($this->approver == $me->id)) {
            if ($this->task_status == TaskStatus::active || $this->task_status == TaskStatus::overdue
                || $this->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if (($this->creator_user == $me->id) && ($this->approver == $me->id)) {
            if ($this->task_status == TaskStatus::active || $this->task_status == TaskStatus::overdue
                || $this->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if (($this->creator_user == $me->id) && ($this->responsible_person == $me->id)) {
            if ($this->task_status == TaskStatus::active || $this->task_status == TaskStatus::overdue
                || $this->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if (($this->approver == $me->id) && ($this->responsible_person == $me->id)) {
            if ($this->task_status == TaskStatus::active || $this->task_status == TaskStatus::overdue
                || $this->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if ($this->approver == $me->id) {
            if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays['complete'] = true;
            } else if ($this->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if ($this->responsible_person == $me->id) {
            if ($this->task_status == TaskStatus::active || $this->task_status == TaskStatus::overdue
                || $this->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($this->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        }

        return $buttonArrays;
    }

    public function setButtonActiveStatus($buttonArrays)
    {
        $buttonArrays['start']    = true;
        $buttonArrays['complete'] = true;
        return $buttonArrays;
    }

    public function setButtonOngoingStatus($buttonArrays)
    {
        $buttonArrays['pause']    = true;
        $buttonArrays['complete'] = true;
        return $buttonArrays;
    }

    public function setButtonAwaitingApprovalStatus($buttonArrays)
    {
        $buttonArrays['accepted']   = true;
        $buttonArrays['returnTask'] = true;
        return $buttonArrays;
    }
}
