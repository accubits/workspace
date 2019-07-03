<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 23/03/18
 * Time: 12:25 PM
 */

namespace Modules\TaskManagement\Repositories\Task;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskChecklists;
use Modules\TaskManagement\Entities\TaskComments;
use Modules\TaskManagement\Entities\TaskCommentsLike;
use Modules\TaskManagement\Entities\TaskFile;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\TaskRepeatType;
use Modules\TaskManagement\Entities\TaskRepeatTypeWeekly;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\TaskManagement\Entities\TaskStatusLog;
use Modules\TaskManagement\Http\Requests\ListTaskRequest;
use Modules\TaskManagement\Repositories\ListTaskRepositoryInterface;
use Modules\TaskManagement\Transformers\FetchSubtaskResource;
use Modules\TaskManagement\Transformers\FetchTaskResource;
use Modules\TaskManagement\Transformers\TaskListCollection;
use Modules\TaskManagement\Transformers\TaskResource;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class ListTaskRepository implements ListTaskRepositoryInterface
{

    protected $task;
    protected $content;
    protected $s3BasePath;

    const GET_TASK_COUNT = 'GET_TASK_COUNT';
    const GET_TASK_LIST  = 'GET_TASK_LIST';


    public function __construct()
    {
        $this->overview = array();
        $this->content  = array();
        $this->s3BasePath = env('S3_PATH');
    }

    public function taskListAll(ListTaskRequest $request)
    {
        $user             = Auth::user();
        $tasksParentIdArr = collect();
        $SubTasks         = collect();
        $checklistsGroup  = collect();

        $tasksCount = $this->getTasks(ListTaskRepository::GET_TASK_COUNT, ['request' => $request]);
        $offset = Utilities::getParams()['offset'];
        $limit  = Utilities::getParams()['perPage'];

        $tasks  = $this->getTasks(ListTaskRepository::GET_TASK_LIST,
            ['offset' => $offset, 'limit' => $limit, 'request' => $request]
        );

        $tasksArray       = $tasks->pluck('id')->toArray();
        $assigneesArray   = $this->getAssigneesByTask($tasksArray);

        $tasks = $this->getSubtaskCount($tasks);

        if (request()->has('tab') && request()->tab) {
            $checklists = $this->getCheckLists($tasksArray);
            $checklistsGroup = $checklists->groupBy(TaskChecklists::task_id);
        }

        $tasks->map(function ($task) use ($assigneesArray, $checklistsGroup, $user) {
            $this->setTaskChecklist($checklistsGroup, $task);
            $task->isAllParticipants = (bool) $task->isAllParticipants;

            if (isset($assigneesArray[$task->id])) {
                $task->assignees      = ($assigneesArray[$task->id]);
                $task->assignee_count = count($assigneesArray[$task->id]);
            } else {
                $task->assignees      = [];
                $task->assignee_count = 0;
            }
            $task->userStatusButtons = $this->getUserButtons($task, $user);
            unset($task->responsible_person);
            unset($task->id);
        });


        $response = Utilities::paginate($tasks,
            Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $tasksCount
        );

        $status   = ResponseStatus::OK;
        $response = $response->toArray();

        if (empty($response['data']))
            $status = ResponseStatus::NOT_FOUND;

        $response['task'] =  $response['data'];
        $response         =  Utilities::unsetResponseData($response);

        $response['overview']  = $this->overview;
        $this->content['data'] = $response;
        $this->content['code'] = Response::HTTP_OK;
        $this->content['status'] = $status;
        return $this->content;
    }

    public function getSubtaskCount($tasks)
    {
        return $tasks->addSelect('parentTask.parent_task_id', DB::raw('count(DISTINCT(parentTask.id)) as subtaskCount'))
            ->leftjoin(Task::table. ' as parentTask', 'parentTask.parent_task_id', '=', Task::table. '.id')
            ->get();
    }

    public function setTaskChecklist($checklistsGroup, $task)
    {
        if (!empty($checklistsGroup) && isset($checklistsGroup[$task->id])) {
            $checklistTotal   = $checklistsGroup[$task->id]->count();
            $checklistChecked = $checklistsGroup[$task->id]->where(TaskChecklists::checklist_status, true)->count();
            $task->checklist  = ['total' => $checklistTotal, 'checked' => $checklistChecked];
            return;
        }
        $task->checklist = array('total' => 0, 'checked' => 0);
    }

    /**
     * filter task based on task tab (overview,active,setbyme....)
     * @param $tasks
     * @return mixed
     */
    public function tabs($tasks, $me, $case)
    {
        if (request()->has('tab')) {
            switch (request()->tab) {
                case "setByMe":
                    $tasks = $tasks->Where(Task::table . '.' .Task::creator_user_id, $me->id);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "imResponsible":
                    $tasks = $tasks->Where(Task::table . '.' .Task::responsible_person_id, $me->id);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "highPriority":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $tasks = $tasks->where(Task::table . '.' .Task::priority, true);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "favourites":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $tasks = $tasks->where(Task::table . '.' .Task::favourite, true);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "archive":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, true);
                    break;

                case "overview":
                    //DB::enableQueryLog();

                    if ($case == ListTaskRepository::GET_TASK_LIST) {
                        $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                        $tasksOverview  = clone $tasks;
                        //$tasksOverview  = $this->getTaskByUserCriteria($tasksOverview, $me);
                        $tasksOverview  = $tasksOverview->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
                            ->where(function ($query) use ($me) {
                                $query->orWhere(Task::table . '.' .Task::approver_user_id, $me->id)
                                    ->orWhere(Task::table . '.' .Task::responsible_person_id, $me->id)
                                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $me->id)
                                    ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
                            });
                        $this->overview = $this->tasksOverView($tasksOverview, $me);
                    }

                    $tasks = $tasks->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
                        ->where(function ($query) use ($me) {
                            $query->orWhere(Task::table . '.' .Task::approver_user_id, $me->id)
                                ->orWhere(Task::table . '.' .Task::responsible_person_id, $me->id)
                                ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $me->id)
                                ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
                        });

                    $taskStatusArr = $this->getStatusArray();

                    //task not accept these statuses completed_approved, completed_waiting_approval
                    $tasks = $tasks->where(function ($query)  use ($taskStatusArr) {
                        $query->whereNotIn(Task::table. '.' .Task::task_status_id,
                            [$taskStatusArr[TaskStatus::completed_approved], $taskStatusArr[TaskStatus::completed_waiting_approval]]);
                    });

                    //$tasks->where(Task::table. '.' .Task::end_date, '>=', Carbon::today());
                    $tasks = $tasks->orderBy(DB::raw('CASE WHEN '. Task::table. '.' .Task::task_status_id. ' = '.$taskStatusArr[TaskStatus::overdue].
                    ' THEN 0 ELSE 1 END'));
                    $tasks = $tasks->orderBy(DB::raw('ABS(DATEDIFF('.Task::table. '.' .Task::end_date.', NOW()))'));
                    $tasks = $tasks->orderBy(Task::table. '.' .Task::priority, true);

                    //$tasks->get();
                    //dd(DB::getQueryLog());
                    break;

                case "completed":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $taskStatus = TaskStatus::whereIn(TaskStatus::table. '.' .TaskStatus::title,
                        [TaskStatus::completed_approved, TaskStatus::completed_waiting_approval])
                        ->select(TaskStatus::table. '.id')
                        ->get();
                    $taskStatusArr = $taskStatus->pluck('id')->toArray();
                    $tasks = $tasks->whereIn(Task::table . '.' .Task::task_status_id, $taskStatusArr);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "activeTasks":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $taskStatusArr = $this->getStatusArray();
                    $tasks = $tasks->whereIn(Task::table . '.' .Task::task_status_id,
                        [$taskStatusArr[TaskStatus::active], $taskStatusArr[TaskStatus::ongoing]]);
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                case "approver":
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
                    $taskStatusArr = $this->getStatusArray();
                    $tasks = $tasks->where(function ($query)  use ($taskStatusArr, $me) {
                        $query->whereIn(Task::table . '.' .Task::task_status_id,
                            [$taskStatusArr[TaskStatus::completed_waiting_approval]])
                        ->where(Task::table . '.' .Task::approver_user_id, $me->id);
                    });
                    $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
                    break;

                default:
                    $tasks = $this->getTaskByUserCriteria($tasks, $me);
            }

        } else {
            $tasks = $this->getTaskByUserCriteria($tasks, $me);
        }

        return $tasks;
    }

    public function tasksOverView($tasks, $user)
    {
        $tasksCollection   = $tasks->get();
        $groupedTaskStatus = $tasksCollection->groupBy('task_status');
        $priority  = $tasksCollection->where(Task::priority, true)->count();
        $favourite = $tasksCollection->where(Task::favourite, true)->count();
        $imResponsible = $tasksCollection->where('responsible_person', $user->id)->count();

        $completed = 0;
        /*if (isset($groupedTaskStatus[TaskStatus::completed_approved])) {
            $completed = $groupedTaskStatus[TaskStatus::completed_approved]->count();
        }*/

        if (isset($groupedTaskStatus[TaskStatus::completed_waiting_approval])) {
            $completed += $groupedTaskStatus[TaskStatus::completed_waiting_approval]->count();
        }


        $taskOverviewArr['active']    = (isset($groupedTaskStatus[TaskStatus::active])) ? $groupedTaskStatus[TaskStatus::active]->count() : 0;
        $taskOverviewArr['overdue']   = (isset($groupedTaskStatus[TaskStatus::overdue])) ? $groupedTaskStatus[TaskStatus::overdue]->count() : 0;
        $taskOverviewArr['completed'] = $completed;
        $taskOverviewArr['priority']  = $priority;
        $taskOverviewArr['favourite']   = $favourite;
        $taskOverviewArr['responsible'] = $imResponsible;

        return $taskOverviewArr;
    }

    /**
     * get tasks by user in  (creator_user_id or responsible_user_id or task_participants or approver_user_id)
     * @param $tasks
     * @param $me
     * @return mixed
     */
    public function getTaskByUserCriteria($tasks, $me)
    {

        //groupBy/distinct
        //DB::enableQueryLog();
        return $tasks->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
            ->where(function ($query) use ($me) {
                $query->orWhere(Task::table . '.' .Task::creator_user_id, $me->id)
                    ->orWhere(Task::table . '.' .Task::approver_user_id, $me->id)
                    ->orWhere(Task::table . '.' .Task::responsible_person_id, $me->id)
                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $me->id)
                    ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
            });
        //dd(DB::getQueryLog());
    }

    public function getTasks($case, $options = [])
    {
        $me = Auth::user();
        $tasks = DB::table(Task::table)
            ->select(
                Task::table. '.id',
                Task::table. '.' .Task::slug,
                Task::table. '.'. Task::title,
                TaskStatus::table. '.'. TaskStatus::title. ' as task_status',
                DB::raw("unix_timestamp(".Task::table . '.'.Task::end_date.") AS due_date"),
                'creator.' . User::name. ' as creator',
                'creator.' . User::slug. ' as creatorSlug',
                'responsible.' . User::name. ' as responsible',
                'responsible.' . User::slug. ' as responsibleSlug',
                Task::table. '.'. Task::creator_user_id. ' as creator_user',
                Task::table. '.'. Task::responsible_person_id . ' as responsible_person',
                Task::table. '.'. Task::approver_user_id . ' as approver',
                //User::table. '.'. User::name. ' as creator',
                Task::table. '.'. Task::parent_task_id,
                Task::table. '.'. Task::priority,
                Task::table. '.'. Task::favourite,
                Task::table. '.'. Task::archive,
                Task::table. '.'. Task::responsible_person_time_change.' as responsibleCanChangeDueDate',
                DB::raw('concat("'.$this->s3BasePath.'",creatorImage.'. UserProfile::image_path .') as creatorImage'),
                DB::raw('concat("'.$this->s3BasePath.'",responsibleImage.'. UserProfile::image_path .') as responsibleImage'),
                DB::raw("unix_timestamp(".Task::table . '.'.Task::CREATED_AT.") AS created_at"),
                Task::table. '.'. Task::is_to_allemployees . ' as isAllParticipants'
            )
            ->join(Organization::table, Organization::table. '.id', '=', Task::table. '.' .Task::org_id)
            ->join(TaskStatus::table, TaskStatus::table. '.id', '=', Task::table. '.'. Task::task_status_id)
            ->join(User::table. ' as creator', 'creator.id', '=', Task::table. '.'. Task::creator_user_id)
            ->join(User::table. ' as responsible', 'responsible.id', '=', Task::table. '.'. Task::responsible_person_id)
            ->leftjoin(UserProfile::table. ' as creatorImage', 'creator.id', '=', 'creatorImage.'. UserProfile::user_id)
            ->leftjoin(UserProfile::table. ' as responsibleImage', 'responsible.id', '=', 'responsibleImage.'. UserProfile::user_id)
            ->where(function ($query) {
                $query->whereNull(Task::table. '.'. Task::parent_task_id)
                    ->where(Task::table. '.'. Task::is_template, false);
            })
            ->where(Organization::table. '.' .Organization::slug, $options['request']->orgSlug)
            ->groupBy(Task::table. '.id');

        $tasks = $this->tabs($tasks, $me, $case);

        // archive
        if (request()->has('tab') && (request()->tab != 'archive')) {
            $tasks = $tasks->where(Task::table . '.' .Task::archive, false);
        }

        if (!empty($options['request']->filterBy)) {
            $tasks = $this->filterTask($tasks, $options['request']->filterBy);
        }


        if ((request()->has('q')) && (request()->q)) {
            $query = request()->q;


            if ((request()->has('tab')) && ((request()->tab == 'imResponsible')
                    || (request()->tab == 'setByMe'))) {
                $tasks->join(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id');
            }
            $tasks->join(User::table. ' as participants', function ($join) use ($query) {
                $join->on('participants.id', '=', TaskParticipants::table. '.' .TaskParticipants::user_id);
                $join->where(function ($where) use ($query) {
                    $where->orWhere('creator.'. User::name, 'like', "%{$query}%")
                        ->orWhere(Task::table. '.'. Task::title, 'like', "%{$query}%")
                        ->orWhere('participants.'. User::name, 'like', "%{$query}%");

                });
            })->get();


        }

        if ($case == ListTaskRepository::GET_TASK_COUNT) {
            $taskCount = $tasks->get()->pluck('id');
            $tasks = $taskCount->count();
        } else if ($case == ListTaskRepository::GET_TASK_LIST) {
            //DB::enableQueryLog();
            $tasks->skip($options['offset'])
                ->take($options['limit'])
                ->get();

            $tasks = Utilities::sort($tasks);
            //dd(DB::getQueryLog());
        }
        return $tasks;
    }

    public function filterTask($tasks, $request)
    {
        //DB::enableQueryLog();

        //dd(DB::getQueryLog());

        $tasks->where(function ($query) use ($tasks, $request) {
            if (!empty($request['taskStatus'])) {
                $slugArr = $this->parseSlug($request['taskStatus']);
                $taskStatusArr = $this->getTaskStatus($slugArr)->pluck('id')->toArray();
                $query->whereIn(Task::table. '.'. Task::task_status_id, $taskStatusArr);
            }

            if (isset($request['favourite'])) {
                $query->where(Task::table. '.'. Task::favourite, $request['favourite']);
            }

            if (isset($request['priority'])) {
                $query->where(Task::table. '.'. Task::priority, $request['priority']);
            }

            if (isset($request['includesSubtask'])) {
                if ($request['includesSubtask']) {
                    $query->whereNotNull(Task::table. '.'. Task::parent_task_id);
                } else {
                    $query->whereNull(Task::table. '.'. Task::parent_task_id);
                }
            }

            if (!empty($request['dueDate'])) {
                $query->whereDate(Task::table . '.' .Task::end_date, Carbon::createFromTimestamp($request['dueDate'])->toDateString());
            }

            if (!empty($request['startDate'])) {
                $query->whereDate(Task::table . '.' .Task::start_date, Carbon::createFromTimestamp($request['startDate'])->toDateString());
            }

            if (!empty($request['participants'])) {
                $slugArr = $this->parseSlug($request['participants']);
                $userIdArr = $this->getUsers($slugArr)->pluck('id')->toArray();
                $query->whereIn(TaskParticipants::table . '.' .TaskParticipants::user_id, $userIdArr);
            }

            if (!empty($request['responsiblePerson'])) {
                $slugArr = $this->parseSlug($request['responsiblePerson']);
                $userIdArr = $this->getUsers($slugArr)->pluck('id')->toArray();
                $tasks->whereIn(Task::table . '.' .Task::responsible_person_id, $userIdArr);
            }

            if (!empty($request['createdBy'])) {
                $slugArr = $this->parseSlug($request['createdBy']);
                $userIdArr = $this->getUsers($request['createdBy'])->pluck('id')->toArray();
                $tasks->whereIn(Task::table . '.' .Task::creator_user_id, $userIdArr);
            }

        })->get();

        if (isset($request['includesChecklist']) && $request['includesChecklist']) {
            $tasks->join(TaskChecklists::table, TaskChecklists::table. '.' .TaskChecklists::task_id, '=', Task::table. '.id')->get();
        }

        if (isset($request['withAttachement']) && $request['withAttachement']) {
            $tasks->join(TaskFile::table, TaskFile::table. '.' .TaskFile::task_id, '=', Task::table. '.id')->get();
        }

        if (!empty($request['finishedOn'])) {
            $tasks->join(TaskStatusLog::table, function ($join) use ($request) {
                    $join->on(TaskStatusLog::table. '.' .TaskStatusLog::task_id, '=', Task::table. '.id');
                        $join->where(function ($query) use ($request) {
                            $query->where(TaskStatusLog::table . '.' .TaskStatusLog::current_status_id, 4)
                                ->whereDate(TaskStatusLog::table . '.' .TaskStatusLog::status_log_time, Carbon::createFromTimestamp($request['finishedOn'])->toDateString());
                        });

                })
                ->get();
        }

        return $tasks;
    }

    /**
     * get slugs from request key and returns as array
     * @param array $requestArr
     * @return array
     */
    public function parseSlug(array $requestArr)
    {
        $slugs = collect();
        collect($requestArr)->each(function ($request) use ($slugs) {
            $slugs[] = isset($request['slug'])? $request['slug'] : [];
        });

        return $slugs->toArray();
    }

    public function getUsers(array $slugArr)
    {
        return DB::table(User::table)
            ->select(
                User::table. '.id'
            )
            ->whereIn(User::slug, $slugArr)
            ->get();
    }

    public function getTaskStatus($slug)
    {
        return DB::table(TaskStatus::table)
            ->select(
                TaskStatus::table. '.id'
            )
            ->whereIn(TaskStatus::slug, $slug)
            ->get();
    }



    /** ---------------- Show a particular task */

    public function getAssigneesByTask(array $tasksArray)
    {
        $participants = $this->getParticitants($tasksArray);
        $grouped = $participants->groupBy('task_id');
        return $grouped->toArray();
    }
//
    public function getParticitants($tasksArray)
    {
        $participants = DB::table(TaskParticipants::table)
            ->select(
                User::table. '.'. User::name. ' as assignee_name',
                User::table. '.'. User::slug. ' as participant_id',
                TaskParticipants::table. '.'. TaskParticipants::task_id,
                DB::raw('concat("'.$this->s3BasePath.'",participantImage.'. UserProfile::image_path .') as participantImage')
            )
            ->join(User::table, User::table. '.id', '=', TaskParticipants::table. '.'. TaskParticipants::user_id)
            ->leftjoin(UserProfile::table. ' as participantImage', User::table.'.id', '=', 'participantImage.'. UserProfile::user_id)
            ->whereIn(TaskParticipants::task_id, $tasksArray)
            ->get();
        return $participants;
    }

    public function getSubTasks($task)
    {
        return DB::table(Task::table)
            ->select(
                Task::table. '.'. Task::slug,
                Task::table. '.'. Task::title
            )
            ->where(Task::parent_task_id, $task)
            ->get();
    }

    public function getCheckLists(array $task)
    {
        return DB::table(TaskChecklists::table)
            ->select(
                TaskChecklists::table. '.'. TaskChecklists::description,
                TaskChecklists::table. '.'. TaskChecklists::slug,
                TaskChecklists::table. '.'. TaskChecklists::checklist_status,
                TaskChecklists::table. '.'. TaskChecklists::task_id
            )
            ->whereIn(TaskChecklists::table. '.' .TaskChecklists::task_id, $task)
            ->get();
    }

    public function getRepeatTask($task)
    {
        return DB::table(TaskRepeat::table)
            ->select(
                TaskRepeatType::table. '.'. TaskRepeatType::title. ' as repeat_type',
                TaskRepeat::table. '.'. TaskRepeat::repeat_every,
                TaskRepeat::table. '.'. TaskRepeat::ends_never,
                TaskRepeat::table. '.'. TaskRepeat::ends_on,
                TaskRepeat::table. '.'. TaskRepeat::ends_after
            )
            ->join(TaskRepeatType::table, TaskRepeatType::table. '.id', '=', TaskRepeat::table. '.'. TaskRepeat::task_repeat_type_id)
            ->where(TaskRepeat::task_id, $task)
            ->first();
    }

    public function getRepeatWeeks($task)
    {
        return DB::table(TaskRepeatTypeWeekly::table)
            ->select(
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::sunday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::monday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::tuesday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::wednesday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::thursday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::friday,
                TaskRepeatTypeWeekly::table. '.'. TaskRepeatTypeWeekly::saturday
            )
            ->where(TaskRepeatTypeWeekly::task_id, $task)
            ->first();
    }

    public function getTask()
    {
        return DB::table(Task::table)
            ->select(
                Task::table. '.id',
                Task::table. '.' .Task::slug,
                Task::table. '.'. Task::title,
                Task::table. '.'. Task::description,
                Task::table. '.'. Task::start_date,
                Task::table. '.'. Task::end_date,
                User::table. '.'. User::name. ' as responsible_person',
                User::table. '.'. User::slug. ' as responsible_person_id',
                Task::table. '.'. Task::parent_task_id,
                Task::table. '.'. Task::responsible_person_time_change,
                Task::table. '.'. Task::approve_task_completed,
                Task::table. '.'. Task::priority,
                Task::table. '.'. Task::favourite,
                Task::table. '.'. Task::is_to_allemployees . ' as isAllParticipants'
            )
            ->join(User::table, User::table. '.id', '=', Task::table. '.'. Task::responsible_person_id);
    }

    /**
     * @param $task
     * @return array
     */
    public function showTask($task)
    {
        try {
            $task   = $this->getTask()
                ->addSelect(
                    Task::table. '.' .Task::org_id,
                    Task::table. '.' .Task::reminder,
                    Task::table. '.' .Task::parent_task_id,
                    'creator.'. User::name . ' as creator',
                    'creator.'. User::slug . ' as creator_slug',
                    'approver.'. User::name . ' as approver',
                    'approver.'. User::slug . ' as approver_slug'
                )
                ->join(User::table. ' as creator', 'creator.id', '=', Task::table. '.'. Task::creator_user_id)
                ->join(User::table. ' as approver', 'approver.id', '=', Task::table. '.'. Task::approver_user_id)
                ->where(Task::table. '.' .Task::slug, $task)
                ->firstOrFail();



            $task->assignees  = $this->getParticitants([$task->id])->toArray();

            $task->checklists = [];
            $task->repeatable = [];
            $task->files      = [];

            $task->checklists = ($this->getCheckLists(array($task->id))->toArray()) ?? array();

            /*if ($this->getSubTasks($task->id)) {
                $task->subtasks = $this->getSubTasks($task->id)->toArray();
            }*/

            $task->files = $this->getTaskFiles($task->id);

            $repeatTaskArr = $this->getRepeatTask($task->id);
            if ($repeatTaskArr) {
                $repeatArray = $repeatTaskArr;
                $ends = array();

                if ($repeatArray->{TaskRepeat::ends_never}) {
                    $ends = array(
                        'never' => (boolean) $repeatArray->{TaskRepeat::ends_never},
                        'on' => "",
                        'after' => ""
                        );
                } else if ($repeatArray->{TaskRepeat::ends_on}) {
                    $repeatEndsOn = $repeatArray->{TaskRepeat::ends_on};
                    $ends = array(
                        'never' => false,
                        'on' => Carbon::parse($repeatEndsOn)->timestamp,
                        'after' => ""
                        );
                } else {
                    $ends = array(
                        'never' => false,
                        'on' => "",
                        'after' => $repeatArray->{TaskRepeat::ends_after}
                        );
                }

                $repeatable = [
                    'repeat_type'  => $repeatArray->repeat_type,
                    'repeat_every' => $repeatArray->{TaskRepeat::repeat_every}
                ];

                if ($repeatArray->repeat_type == TaskRepeatType::WEEK) {
                    $repeatable['week'] = $this->getRepeatWeeks($task->id);
                }

                $repeatable['ends'] = $ends;
                $task->repeatable   = $repeatable;
            }

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_NOT_FOUND;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }


        $response = new TaskResource($task);
        $this->content['data']   = $response;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = ResponseStatus::OK;
        return $this->content;
    }

    public function getTaskFiles($taskId)
    {
        return DB::table(TaskFile::table)
            ->select(TaskFile::table. '.' .TaskFile::taskfile_slug,
                TaskFile::table. '.' .TaskFile::filename,
                TaskFile::table. '.' .TaskFile::filesize)
            ->where(TaskFile::table. '.' .TaskFile::task_id, $taskId)
            ->get();
    }

    public function getTaskFilesMap()
    {

    }

    /** Fetch   */

    public function fetch($request)
    {
        $me     = Auth::user();
        $status = ResponseStatus::OK;
        $taskId = $request->task_slug;
        $task   = $this->getTask()
            ->addSelect(
                Task::table. '.id',
                Task::table. '.'. Task::org_id,
                Task::table. '.'. Task::priority,
                Task::table. '.'. Task::favourite,
                Task::table. '.'. Task::creator_user_id. ' as creator_user',
                'responsiblePerson.'. User::name . ' as responsible_person_name',
                Task::table. '.'. Task::responsible_person_id . ' as responsible_person',
                DB::raw('concat("'.$this->s3BasePath.'",responsiblePersonProfile.'. UserProfile::image_path . ') as responsiblePersonImage'),
                Task::table. '.'. Task::approver_user_id . ' as approver',
                Task::table. '.'. Task::approve_task_completed . ' as isApprover',
                'creator.'. User::name . ' as creator',
                'creator.'. User::slug . ' as creator_slug',
                DB::raw('concat("'.$this->s3BasePath.'",creatorProfile.'. UserProfile::image_path .') as creatorImage'),
                'approver.'. User::name . ' as approverName',
                'approver.'. User::slug . ' as approverSlug',
                'completedPerson.'. User::name . ' as completedUser',
                DB::raw('concat("'.$this->s3BasePath.'",completedPersonProfile.'. UserProfile::image_path .') as completedPersonImage'),
                DB::raw('concat("'.$this->s3BasePath.'",approverProfile.'. UserProfile::image_path . ') as approverImage'),
                Task::table. '.'. Task::title. ' as task_status',
                Task::table. '.'. Task::reminder,
                Task::table. '.'. Task::responsible_person_time_change.' as responsibleCanChangeDueDate',
                Task::table. '.created_at',
                TaskStatus::table. '.'. Task::title. ' as task_status',
                Task::table. '.'. Task::repeat,
                DB::raw("(CASE WHEN (creator.id = ".$me->id.") THEN true ELSE false END) as isEditable"),
                Task::table. '.'. Task::is_to_allemployees . ' as isAllParticipants'
            )
            ->join(TaskStatus::table, TaskStatus::table. '.id', '=', Task::table. '.'. Task::task_status_id)
            ->join(User::table. ' as creator', 'creator.id', '=', Task::table. '.'. Task::creator_user_id)
            ->join(User::table. ' as responsiblePerson', 'responsiblePerson.id', '=', Task::table. '.'. Task::responsible_person_id)
            ->leftjoin(User::table. ' as approver', 'approver.id', '=', Task::table. '.'. Task::approver_user_id)
            ->leftjoin(User::table. ' as completedPerson', 'completedPerson.id', '=', Task::table. '.'. Task::task_completed_user_id)
            ->leftjoin(UserProfile::table. ' as creatorProfile', 'creatorProfile.' .UserProfile::user_id, '=', 'creator.id')
            ->leftjoin(UserProfile::table. ' as responsiblePersonProfile', 'responsiblePersonProfile.' .UserProfile::user_id, '=', 'responsiblePerson.id')
            ->leftjoin(UserProfile::table. ' as approverProfile', 'approverProfile.' .UserProfile::user_id, '=', 'approver.id')
            ->leftjoin(UserProfile::table. ' as completedPersonProfile', 'completedPersonProfile.' .UserProfile::user_id, '=', 'completedPerson.id')
            ->where(Task::table. '.' .Task::slug, $taskId)
            ->firstOrFail();


        $task->assignees  = $this->getParticitants([$task->id])->toArray();

        $task->checklists = [];
        $task->checklists = ($this->getCheckLists(array($task->id))) ?? array();

        $response = new FetchTaskResource($task);

        if (!$response)
            $status = ResponseStatus::NOT_FOUND;

        $this->content['data']   = $response;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = $status;
        return $this->content;
    }

    public function getTemplates()
    {
        $user = Auth::user();
        $task = DB::table(Task::table)
            ->select(Task::table. '.' .Task::slug, Task::table. '.' .Task::title)
            ->where(Task::table. '.' .Task::is_template, true)
            ->groupBy(Task::table. '.id');

        $task = $this->getTaskByUserCriteria($task, $user)->get();
        if (count($task) == 0) {
            $this->content['data']    =  [];
            $this->content['code']    =  Response::HTTP_OK;
            $this->content['status']  =  ResponseStatus::NOT_FOUND;
            return $this->content;
        }
        $this->content['data']   = $task;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = ResponseStatus::OK;
        return $this->content;
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $request
     * @return array
     */
    public function getSubtaskFromTask($request)
    {
        $task     = $this->getSingleTask($request->task_slug);
        $subtasks = $this->getSubtasksByTask($task);
        $this->content['data']   = $subtasks;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = ResponseStatus::OK;
        return $this->content;
    }

    public function getSubtasksByTask($task)
    {
        $subTasks = DB::table(Task::table)
            ->select(
                Task::table. '.id',
                Task::table. '.' .Task::slug,
                'parentTaskTable.' .Task::slug. ' as parentTaskSlug',
                Task::table. '.' .Task::title,
                Task::table. '.' .Task::priority,
                Task::table. '.' .Task::favourite,
                Task::table. '.' .Task::end_date.' as endDate',
                'creator.name as creatorName',
                DB::raw('concat("'.$this->s3BasePath.'",creatorProfile.'. UserProfile::image_path .') as creatorImage'),
                'responsible.name as responsiblePersonName',
                'approver.name as approverName',
                Task::table. '.'. Task::is_to_allemployees . ' as isAllParticipants',
                DB::raw("unix_timestamp(".Task::table . '.'.Task::CREATED_AT.") AS created_at")
                )
            ->leftjoin(Task::table. ' as parentTaskTable', 'parentTaskTable.id', '=', Task::table. '.'. Task::parent_task_id)
            ->join(User::table. ' as creator', 'creator.id', '=', Task::table. '.'. Task::creator_user_id)
            ->join(User::table. ' as responsible', 'responsible.id', '=', Task::table. '.'. Task::responsible_person_id)
            ->join(User::table. ' as approver', 'approver.id', '=', Task::table. '.'. Task::approver_user_id)
            ->leftjoin(UserProfile::table. ' as creatorProfile', 'creatorProfile.' .UserProfile::user_id, '=', 'creator.id')
            ->where(Task::table. '.' .Task::parent_task_id, $task->id)
            ->where(Task::table. '.'. Task::is_template, false)
            ->where(Task::table . '.' .Task::archive, false)
            ->get();


        $tasksArray       = $subTasks->pluck('id')->toArray();
        $assigneesArray   = $this->getAssigneesByTask($tasksArray);

        $subTasks->map(function ($subTask) use ($assigneesArray) {
            if (isset($assigneesArray[$subTask->id])) {
                $subTask->people['assignees']   = ($assigneesArray[$subTask->id]);
                $subTask->peopleCount           = count($assigneesArray[$subTask->id]);
                $subTask->subtaskCount          = $this->getSubTasks($subTask->id)->count();
                $subTask->isAllParticipants     = (bool) $subTask->isAllParticipants;

            } else {
                $subTask->people['assignees']   = [];
                $subTask->peopleCount           = 0;
                $subTask->subtaskCount          = $this->getSubTasks($subTask->id)->count();
                $subTask->isAllParticipants     = (bool) $subTask->isAllParticipants;
            }

        });

        return $subTasks;
    }

    public function getSingleTask($slug)
    {
        return Task::select(Task::table. '.id')
            ->where(Task::table. '.' .Task::slug, $slug)
            ->firstOrFail();
    }

    /** @TODO needs to complete - task history in details page
     * @param \GuzzleHttp\Psr7\Request $request
     */
    public function taskHistory($request)
    {
        $task = $this->getSingleTask($request->task_slug);

        $taskStatusLog = DB::table(TaskStatusLog::table)
            ->select(
                TaskStatusLog::table. '.' .TaskStatusLog::previous_status_id,
                TaskStatusLog::table. '.' .TaskStatusLog::current_status_id,
                User::table. '.' .User::name
            )
            ->join(User::table. '.id', '=', TaskStatusLog::table. '.'. TaskStatusLog::user_id)
            ->where(TaskStatusLog::table. '.' .TaskStatusLog::task_id, $task->id)
            ->get();
        dd($taskStatusLog);
        // TODO: Implement taskHistory() method.
    }

    /**
     * get status and return as array with status title as key
     * @return array
     */
    public function getStatusArray()
    {
        $taskStatusArr = collect();
        $taskStatuses = TaskStatus::select(TaskStatus::table. '.id', TaskStatus::table. '.' .TaskStatus::title)
            ->get();

        $taskStatuses->map(function ($status)  use ($taskStatusArr) {
            $taskStatusArr[$status->title] = $status->id;
        });

        return $taskStatusArr->toArray();
    }

    /**********************************Task User Buttons*************************************/


    /**
     * @param $task
     * @param $me
     * @return array
     */
    public function getUserButtons($task, $me)
    {
        $buttonArrays = ['start' => false, 'pause' => false, 'complete' => false, 'accepted' => false,
            'returnTask' => false
        ];

        if (($task->creator_user == $me->id) && ($task->responsible_person == $me->id) && ($task->approver == $me->id)) {
            if ($task->task_status == TaskStatus::active || $task->task_status == TaskStatus::overdue
                || $task->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if (($task->creator_user == $me->id) && ($task->approver == $me->id)) {
            if ($task->task_status == TaskStatus::active || $task->task_status == TaskStatus::overdue
                || $task->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if (($task->creator_user == $me->id) && ($task->responsible_person == $me->id)) {
            if ($task->task_status == TaskStatus::active || $task->task_status == TaskStatus::overdue
                || $task->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if (($task->approver == $me->id) && ($task->responsible_person == $me->id)) {
            if ($task->task_status == TaskStatus::active || $task->task_status == TaskStatus::overdue
                || $task->task_status == TaskStatus::pause) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::ongoing) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if ($task->approver == $me->id) {
            if ($task->task_status == TaskStatus::ongoing) {
                $buttonArrays['complete'] = true;
            } else if ($task->task_status == TaskStatus::completed_waiting_approval) {
                $buttonArrays = $this->setButtonAwaitingApprovalStatus($buttonArrays);
            }
        } else if ($task->responsible_person == $me->id) {
            if ($task->task_status == TaskStatus::active || $task->task_status == TaskStatus::overdue
                || $task->task_status == TaskStatus::pause  ) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->task_status == TaskStatus::ongoing) {
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