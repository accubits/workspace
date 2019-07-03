<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 7/2/18
 * Time: 12:04 PM
 */

namespace Modules\TaskManagement\Repositories\Task;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\FileUpload;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\SocialModule\Repositories\Common\CommonRepository;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskChecklists;
use Modules\TaskManagement\Entities\TaskFile;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\TaskRepeatType;
use Modules\TaskManagement\Entities\TaskRepeatTypeWeekly;
use Modules\TaskManagement\Entities\TaskScore;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\TaskManagement\Entities\TaskStatusLog;
use Modules\TaskManagement\Http\Requests\createRequestBulkDelete;
use Modules\TaskManagement\Http\Requests\CreateTaskRequest;
use Modules\TaskManagement\Http\Requests\UpdateTaskRequest;
use Modules\TaskManagement\Repositories\TaskRepositoryInterface;
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class TaskRepository implements TaskRepositoryInterface
{

    protected $task;
    protected $content;
    protected $actstreamCommon;


    public function __construct(CommonRepository $common)
    {
        $this->content = array();
        $this->actstreamCommon = $common;
    }

    /**
     * Create Task functionality
     * @param CreateTaskRequest $request
     * @return array
     */
    public function createTask(CreateTaskRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $isTemplate = $request->is_template;
            $request->is_template = false;
            $task  = $this->addTask($request, $user);
            $task->save();

            $this->addTaskStatusLog($task, $user);

            if ($request->file) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->file('file'));
                $taskFileArray = $this->taskFileUpload($requestCollectionObj, $task, $request->org_slug);
                TaskFile::insert($taskFileArray);
            }

            //existing template files
            if ($request->existingFiles) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->existingFiles);
                $taskFileArray = $this->templateFileUpload($requestCollectionObj, $task, $request->org_slug);
                TaskFile::insert($taskFileArray);

            }

            if ($request->checklist) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->checklist);
                $checklistArray = $this->addCheckList($requestCollectionObj, $task, $user);
                TaskChecklists::insert($checklistArray);
            }

            if ($request->participants) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->participants);
                $participantListArray = $this->addParticipants($requestCollectionObj, $task, $user);
                TaskParticipants::insert($participantListArray);
            }

            /*else {
                $requestCollectionObj = $this->getRequestCollectionObject($user->slug);
                $participantListArray = $this->addParticipants($requestCollectionObj, $task, $user);
                TaskParticipants::insert($participantListArray);
            }*/

/*            if ($request->has('subtask')) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->subtask);
                $this->addSubTask($requestCollectionObj, $task, $user);
            }*/

            if ($request->repeatable) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->repeatable);
                $this->repeatable($requestCollectionObj, $task, $user);
            }

            if ($isTemplate) {
                $this->addToTemplate($request, $user);
            }

            $this->setDataForActivityStream($task, "taskCreated");

            DB::commit();
        }  catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'Task added successfully'),
            'code'   => Response::HTTP_CREATED,
            'status' => ResponseStatus::OK
        );

    }

    /**
     * @param $task
     * @param $note - taskCreated|taskUpdated
     */
    public function setDataForActivityStream($task, $note)
    {
        $userArrs = array();
        $orgId          = $task->{Task::org_id};

        $creatorUserObj = Auth::user();
        $orgObj         = Organization::where('id', $orgId)->firstOrFail();
        $participants = DB::table(TaskParticipants::table)->select(TaskParticipants::user_id)
            ->where(TaskParticipants::table. '.' .TaskParticipants::task_id, $task->id)
            ->get()
            ->pluck(TaskParticipants::user_id);

        if (!in_array($task->{Task::responsible_person_id}, $userArrs))
            array_push($userArrs, $task->{Task::responsible_person_id});

        if (!empty($task->{Task::approver_user_id}) && !in_array($task->{Task::approver_user_id}, $userArrs))
            array_push($userArrs, $task->{Task::approver_user_id});

        foreach ($participants as $participant) {
            if (!in_array($participant, $userArrs))
                $userArrs[] = $participant;
        }

        $userIdCollection = collect($userArrs);

        $this->actstreamCommon->setSocialActivityStreamTask($creatorUserObj, $orgObj, $task, $userIdCollection, $note);

    }

    /**
     * Add To Template which create same copy of tasks
     * @param $request
     * @param $user
     */
    public function addToTemplate($request, $user)
    {
        DB::table(Task::table)->where(Task::title, $request->title)
            ->where(Task::creator_user_id, $user->id)
            ->where(Task::is_template, true)->delete();

        $request->is_template = true;
        $templateTask  = $this->addTask($request, $user);
        $templateTask->save();

        $this->addTaskStatusLog($templateTask, $user);

        if ($request->file) {
            $requestCollectionObj = $this->getRequestCollectionObject($request->file('file'));
            $taskFileArray = $this->taskFileUpload($requestCollectionObj, $templateTask, $request->org_slug);
            TaskFile::insert($taskFileArray);
        }

        //existing template files
        if ($request->existingFiles) {
            $requestCollectionObj = $this->getRequestCollectionObject($request->existingFiles);
            $taskFileArray = $this->templateFileUpload($requestCollectionObj, $templateTask, $request->org_slug);
            TaskFile::insert($taskFileArray);

        }

        if ($request->checklist) {
            $requestCollectionObj = $this->getRequestCollectionObject($request->checklist);
            $checklistArray = $this->addCheckList($requestCollectionObj, $templateTask, $user);
            TaskChecklists::insert($checklistArray);
        }

        if ($request->participants) {
            $requestCollectionObj = $this->getRequestCollectionObject($request->participants);
            $participantListArray = $this->addParticipants($requestCollectionObj, $templateTask, $user);
            TaskParticipants::insert($participantListArray);
        } /*else {
            $requestCollectionObj = $this->getRequestCollectionObject($user->slug);
            $participantListArray = $this->addParticipants($requestCollectionObj, $templateTask, $user);
            TaskParticipants::insert($participantListArray);
        }*/

        if ($request->repeatable) {
            $requestCollectionObj = $this->getRequestCollectionObject($request->repeatable);
            $this->repeatable($requestCollectionObj, $templateTask, $user);
        }
    }


    public function taskFileUpload($collectionObj, $task, $orgSlug)
    {
        $fileUpload = new FileUpload;
        $folder     = "{$orgSlug}/task/{$task->slug}";

        return $collectionObj->map(function ($file) use ($fileUpload, $folder, $task) {
            $fileUpload->setPath($folder);
            $fileUpload->setFile($file);
            $fileUpload->s3Upload();

            return array(
                TaskFile::taskfile_slug => Utilities::getUniqueId(),
                TaskFile::task_id   => $task->id,
                TaskFile::filename  => $file->getClientOriginalName(),
                TaskFile::filesize  => filesize($file),
                'created_at' => now(),
                'updated_at' => now()
            );
        })->all();
    }

    public function templateFileUpload($collectionObj, $task, $orgSlug)
    {
/*        $requestFileArr = array();
        foreach ($requestCollectionObjFile as $file) {
            $requestFileArr[] = $file->getClientOriginalName();
        }

        $fileUpload = new FileUpload;
        $folder     = "{$orgSlug}/task/{$task->slug}";

        $collectionObj = $collectionObj->filter(function ($value) use ($requestFileArr) {
            if (in_array($value['name'], $requestFileArr)) {
                return;
            }
        });*/

        $fileUpload = new FileUpload;
        $folder     = "{$orgSlug}/task/{$task->slug}";

        return $collectionObj->map(function ($file) use ($fileUpload, $folder, $task, $orgSlug) {
            $source      = "{$orgSlug}/task/{$file['taskSlug']}/{$file['name']}";
            $destination = "{$folder}/{$file['name']}";

            $fileUpload->copy($source, $destination);

            return array(
                TaskFile::taskfile_slug => Utilities::getUniqueId(),
                TaskFile::task_id   => $task->id,
                TaskFile::filename  => $file['name'],
                TaskFile::filesize  => $file['size'],
                'created_at' => now(),
                'updated_at' => now()
            );
        })->all();
    }

    /**
     * @param $collectionObj
     * @param $task
     * @param $user
     */
    private function repeatable($collectionObj, $task, $user)
    {
        TaskRepeat::where(TaskRepeat::task_id, $task->id)
            ->where(TaskRepeat::user_id, $user->id)->delete();

        $taskRepeat = new TaskRepeat;
        $taskRepeat->{TaskRepeat::task_id} = $task->id;
        $taskRepeat->{TaskRepeat::user_id} = $user->id;

        if ($collectionObj['repeat_type']) {
            $repeatType = $this->getRepeatType($collectionObj['repeat_type']);
            $taskRepeat->{TaskRepeat::task_repeat_type_id} = $repeatType->id;

            if ($repeatType->title == 'week' && $collectionObj->has('week')) {
                $this->updateWeek($collectionObj['week'], $task->id);
            }
        }

        if ($collectionObj['repeat_every'])
            $taskRepeat->{TaskRepeat::repeat_every} = $collectionObj['repeat_every'];

        if (isset($collectionObj['ends']['never']) && $collectionObj['ends']['never'] == TRUE)
            $taskRepeat->{TaskRepeat::ends_never} = TRUE;
        else if (isset($collectionObj['ends']['on']))
            $taskRepeat->{TaskRepeat::ends_on} = $collectionObj['ends']['on'];
        else if (isset($collectionObj['ends']['after']))
            $taskRepeat->{TaskRepeat::ends_after} = $collectionObj['ends']['after'];

        $taskRepeat->save();
        return;
    }

    /**
     * Update task repeat
     * @param $collectionObj
     * @param $task
     * @param $user
     */
    private function updateRepeatable($collectionObj, $task, $user)
    {
        $taskRepeat = TaskRepeat::where(TaskRepeat::task_id, $task->id)
            ->where(TaskRepeat::user_id, $user->id)
            ->firstOrFail();

        if ($collectionObj['repeat_type']) {
            $repeatType = $this->getRepeatType($collectionObj['repeat_type']);
            $taskRepeat->{TaskRepeat::task_repeat_type_id} = $repeatType->id;

            if ($repeatType->title == 'week' && $collectionObj->has('week')) {
                $this->updateWeek($collectionObj['week'], $task->id);
            } else ($task->repeatTypeWeekly) ? $task->repeatTypeWeekly->delete() : null ;

        }

        if ($collectionObj['repeat_every'])
            $taskRepeat->{TaskRepeat::repeat_every} = $collectionObj['repeat_every'];

        //get ends , never/on/after from request collection
        $endsChoice = $this->updateRepeatEndsChoice($collectionObj);
        $taskRepeat = $this->updateRepeatEnds($taskRepeat, $collectionObj, $endsChoice);

        $taskRepeat->save();
        return;
    }

    public function updateWeek($collection, $taskId)
    {
        TaskRepeatTypeWeekly::updateOrCreate(
            [
                TaskRepeatTypeWeekly::task_id => $taskId
            ],
            [
                TaskRepeatTypeWeekly::sunday    => $collection['Sunday'],
                TaskRepeatTypeWeekly::monday    => $collection['Monday'],
                TaskRepeatTypeWeekly::tuesday   => $collection['Tuesday'],
                TaskRepeatTypeWeekly::wednesday => $collection['Wednesday'],
                TaskRepeatTypeWeekly::thursday => $collection['Thursday'],
                TaskRepeatTypeWeekly::friday   => $collection['Friday'],
                TaskRepeatTypeWeekly::saturday => $collection['Saturday'],
                TaskRepeatTypeWeekly::task_id => $taskId,
            ]
        );
        return;
    }

    /**
     * return choice (never/on/after) corresponding to request
     * @param $collectionObj
     * @return null|string
     */
    public function updateRepeatEndsChoice($collectionObj)
    {
        $choice = NULL;
        if (isset($collectionObj['ends']['never']) && $collectionObj['ends']['never'] == TRUE) {
            $choice = 'never';
        } else if (isset($collectionObj['ends']['on'])) {
            $choice = 'on';
        } else if (isset($collectionObj['ends']['after'])) {
            $choice = 'after';
        }

        return $choice;
    }

    /**
     * Update ends choices never/on/after by giving parameter choice
     * @param TaskRepeat $taskRepeatObject
     * @param $collection
     * @param $choice
     * @return TaskRepeat
     */
    public function updateRepeatEnds(TaskRepeat $taskRepeatObject, $collection, $choice)
    {
        switch ($choice) {
            case 'never' :
                $taskRepeatObject->{TaskRepeat::ends_never} = 1;
                $taskRepeatObject->{TaskRepeat::ends_on}    = null;
                $taskRepeatObject->{TaskRepeat::ends_after} = NULL;
                return $taskRepeatObject;

            case 'on' :
                $taskRepeatObject->{TaskRepeat::ends_never} = 0;
                $taskRepeatObject->{TaskRepeat::ends_on} = $collection['ends']['on'];
                $taskRepeatObject->{TaskRepeat::ends_after} = NULL;
                return $taskRepeatObject;

            case 'after' :
                $taskRepeatObject->{TaskRepeat::ends_never} = 0;
                $taskRepeatObject->{TaskRepeat::ends_on} = NULL;
                $taskRepeatObject->{TaskRepeat::ends_after} = $collection['ends']['after'];
                return $taskRepeatObject;
        }
    }

    /**
     * get repeat type - day/week/month/year
     * @param $repeatType
     * @return mixed
     */
    private function getRepeatType($repeatType)
    {
        return TaskRepeatType::where(TaskRepeatType::title, $repeatType)->firstOrFail();
    }

    /**
     * Update Task main function
     * @param CreateTaskRequest $request
     * @return array
     */
    public function updateTask(UpdateTaskRequest $request)
    {
        DB::beginTransaction();
        try {
            $repeat = $this->checkTask($request->task)->repeat;
            $user   = Auth::user();
            $task   = $this->addTask($request, $user, 'update');
            $task->save();

            if ($request->has('existingFiles')) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->existingFiles);
                $this->deleteFiles($requestCollectionObj, $task, $request->org_slug);
            }

            if ($request->file) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->file('file'));
                $taskFileArray = $this->taskFileUpload($requestCollectionObj, $task, $request->org_slug);
                TaskFile::insert($taskFileArray);
            }

            if ($request->checklist) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->checklist);
                $this->updateCheckList($requestCollectionObj, $task, $user);
            }

            if ($request->participants) {
                $requestCollectionObj = $this->getRequestCollectionObject(array_unique($request->participants));
                $this->updateParticipants($requestCollectionObj, $task);
            }

            if ($request->to_all_participants) {
                DB::table(TaskParticipants::table)->where(TaskParticipants::task_id, $task->id)->delete();
            }

/*            if ($request->has('subtask')) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->subtask);
                $this->addSubTask($requestCollectionObj, $task, $user);
            }*/

            if ($request->repeatable) {
                $requestCollectionObj = $this->getRequestCollectionObject($request->repeatable);

                if ($repeat)
                    $this->updateRepeatable($requestCollectionObj, $task, $user);
                else
                    $this->repeatable($requestCollectionObj, $task, $user);

            } else if ($repeat == true && $request->repeatable == false) {
                TaskRepeat::where(TaskRepeat::task_id, $task->id)
                    ->where(TaskRepeat::user_id, $user->id)->delete();
            }

            $this->setDataForActivityStream($task, "taskUpdated");
            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $error = $e->getMessage();
            if ($e->getModel() === Task::class)
                $error = "Invalid Task";

            $this->content['error']   =  $error;
            $this->content['code']    =  422;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            return $this->content;
        }

        $this->content['data']   =  "Task updated successfully";
        $this->content['code']   =  200;
        return $this->content;

    }

    public function deleteFiles($existingFiles, $task, $orgSlug)
    {
        $existingFileSlugArr = $existingFiles->pluck('fileSlug')->toArray();
        $path = "{$orgSlug}/task/{$task->slug}/";
        $nonExistingFileQuery = TaskFile::select(
            DB::raw("concat('".$path."', ".TaskFile::table.'.'.TaskFile::filename.") as filename")
        )
            ->whereNotIn(TaskFile::taskfile_slug, $existingFileSlugArr)
            ->where(TaskFile::task_id, $task->id);

        $nonExistingFiles = $nonExistingFileQuery->get()->pluck('filename')->toArray();

        if (!empty($nonExistingFiles)) {
            $nonExistingFileQuery->delete();

            $fileUpload = new FileUpload;
            $fileUpload->deleteFiles($nonExistingFiles);
        }

    }

    /**
     * Update Task Status - complete|pause|start
     * @param Request $request
     * @return array
     */
    public function updateTaskStatus($request)
    {
        try {
            $requestStatus = $request->status;
            $user = Auth::user();
            $statusArray = $this->getStatusArray();

            $task = Task::select(Task::table.'.id',
                    Task::table.'.'.Task::org_id,
                    Task::table.'.'.Task::task_status_id,
                    Task::table.'.'.Task::creator_user_id,
                    Task::table.'.'.Task::responsible_person_id,
                    Task::table.'.'.Task::approver_user_id,
                    Task::table.'.'.Task::approve_task_completed
                )
                ->whereIn(Task::table.'.'.Task::slug, $request->tasks)->firstOrFail();

            DB::beginTransaction();

            if ($request->action == 'single') {
                $taskStatus = $this->getTaskStatus($requestStatus);
                $task->{Task::task_status_id} = $taskStatus->id;

                //echo $task->{Task::creator_user_id} . '.' . $task->{Task::responsible_person_id}. '.' .$task->{Task::approver_user_id}. '.' .$user->id;die;
                if (($task->{Task::creator_user_id} == $user->id) && ($task->{Task::responsible_person_id} == $user->id) && ($task->{Task::approver_user_id} == $user->id)) {
                    if ($requestStatus == 'complete') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'accepted') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'returnTask') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::active];
                    }
                } else if (($task->{Task::responsible_person_id} == $user->id) && ($task->{Task::approver_user_id} == $user->id)) {
                    if ($requestStatus == 'complete') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'accepted') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'returnTask') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::active];
                    }
                } else if (($task->{Task::creator_user_id} == $user->id) && ($task->{Task::responsible_person_id} == $user->id)) {
                    if ($requestStatus == 'complete' && $task->{Task::approve_task_completed}) {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'accepted') {
                        throw new \Exception("You have no permission to aprrove", Response::HTTP_UNPROCESSABLE_ENTITY);
                    } else if ($requestStatus == 'returnTask') {
                        throw new \Exception("You have no permission to reject", Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }else if (($task->{Task::creator_user_id} == $user->id) && ($task->{Task::approver_user_id} == $user->id)) {
                    if ($requestStatus == 'complete') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'accepted') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'returnTask') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::active];
                    }
                } else if ($task->{Task::approver_user_id} == $user->id) {

                    if ($requestStatus == 'start') {
                        throw new \Exception("You have no permission to start", Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    if ($requestStatus == 'complete') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'accepted') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'returnTask') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::active];
                    }
                } else if ($task->{Task::responsible_person_id} == $user->id) {
                    if ($requestStatus == 'complete' && $task->{Task::approve_task_completed}) {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'complete') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_waiting_approval];
                        $task->{Task::task_completed_user_id} = $user->id;
                    } else if ($requestStatus == 'accepted') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::completed_approved];
                    } else if ($requestStatus == 'returnTask') {
                        $task->{Task::task_status_id} = $statusArray[TaskStatus::active];
                    }
                } else if ($task->{Task::creator_user_id} == $user->id) {
                    throw new \Exception("Creator cant update a status", Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $task->save();

            $activityStreamNoteVar = "";

            if ($requestStatus == 'start')
                $activityStreamNoteVar = 'taskStarted';
            else if ($requestStatus == 'pause')
                $activityStreamNoteVar = 'taskStarted';
            else if ($requestStatus == 'complete')
                $activityStreamNoteVar = 'taskCompleted';
            else if ($requestStatus == 'accepted')
                $activityStreamNoteVar = 'taskAccepted';
            else if ($requestStatus == 'returnTask')
                $activityStreamNoteVar = 'taskRejected';

            $this->addTaskStatusLog($task, $user);
            $this->setDataForActivityStream($task, $activityStreamNoteVar);

            DB::commit();
        }  catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_UNPROCESSABLE_ENTITY;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        $this->content['data']   =  array(
            'taskStatus'   => $requestStatus,
            'taskStatusId' => $task->{Task::task_status_id},
            'userStatusButtons' => $this->getUserButtons($task, $user, $statusArray)
        );
        $this->content['code']   =  200;
        $this->content['status']  = ResponseStatus::OK;
        return $this->content;

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



    /**
     * request to collection
     * @param $request
     * @return \Illuminate\Support\Collection
     */
    private function getRequestCollectionObject($request)
    {
        return collect($request);
    }

    /**
     * @param $request
     * @param $user
     * @return Task
     */
    public function addTask($request, $user, $action = 'create')
    {
        if ($action == 'update') {
            $task       = $this->checkTask($request->taskId);
        } else {
            $taskStatus = $this->getTaskStatus(TaskStatus::active);
            $task = new Task;
            $task->{Task::slug}           = Utilities::getUniqueId();
            $task->{Task::task_status_id} = $taskStatus->id;
        }

        if ($action == 'update' && $request->action == 'partial_update') {
            $task = $this->partialUpdate($request, $task, $user);

        } else {

            $task->{Task::title} = $request->title;
            $task->{Task::description}   = $request->description;
            $task->{Task::start_date}    = ($request->start_date)? $request->start_date: NULL;
            $task->{Task::end_date}      = $request->end_date;
            $task->{Task::repeat}        = ($request->repeatable) ? true : false;
            $task->{Task::responsible_person_id}   = $user->id;
            $task->{Task::creator_user_id}         = $user->id;

            if (!$request->approve_task_completed) {
                $task->{Task::approver_user_id} = $user->id;
            }

            $task->{Task::org_id}                  = $this->getOrganization($request->org_slug)->id;
            $task->{Task::responsible_person_time_change}   = ($request->responsible_person_change_time) ?? false;
            $task->{Task::approve_task_completed}           = ($request->approve_task_completed) ?? false;
            $task->{Task::priority}      = ($request->priority) ?? false;
            $task->{Task::favourite}     = ($request->favourite) ?? false;
            $task->{Task::is_template}   = ($request->is_template) ?? false;
            $task->{Task::is_to_allemployees}   = ($request->to_all_participants) ?? false;
            $task->{Task::task_score_id}        = $this->getTaskScore()->id;


            $now = Carbon::now();
            $endDt12Hrs = Carbon::createFromTimestamp($request->end_date)->subHours(12);
            $endDt      = Carbon::createFromTimestamp($request->end_date);

            if (($now->gte($endDt12Hrs)) && ($now->lte($endDt))) {
                $task->{Task::priority} = true;
            }

            if ($request->responsible_person) {
                $assignedUser = $this->checkUser($request->responsible_person);
                $task->{Task::responsible_person_id}   = $assignedUser->id;
            }

            if ($request->approver) {
                $approverUser = $this->checkUser($request->approver);
                $task->{Task::approver_user_id}   = $approverUser->id;
            }

            if ($request->has('parent_task')) {
                $task->{Task::parent_task_id}   = empty($request->parent_task)? NULL : $this->checkTask($request->parent_task)->id;
            }

            if ($request->has('reminder')) {
                $task->{Task::reminder}  = empty($request->reminder)? NULL : $request->reminder;
            }
        }

        return $task;
    }

    public function getTaskScore($score = TaskScore::positive)
    {
        return DB::table(TaskScore::table)->select('id')->where(TaskScore::score_name, $score)->first();
    }

    public function TaskPartialUpdate(Request $request)
    {
        $user = Auth::user();
        try {
            $task = $this->checkTask($request->task_slug);

            $task = $this->partialUpdate($request, $task, $user);
            $task->save();

            $this->content['data']   =  array('message' => "Task updated successfully");
            $this->content['code']   =  200;
            $this->content['status']  = ResponseStatus::OK;
            return $this->content;

        } catch (ModelNotFoundException $e) {
            $this->content['error']   =  array(
                "msg" => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }



    }

    public function partialUpdate($request, $task, $user)
    {
        if ($request->title)
            $task->{Task::title} = $request->title;

        if ($request->description)
            $task->{Task::description} = $request->description;

        if ($request->start_date)
            $task->{Task::start_date} = $request->start_date;

        if ($request->end_date) {
            /*if ($request->end_date <= Carbon::now()->timestamp) {
                throw new \Exception("Due Date should not less than current date time");
            }*/

            if (!empty($request->start_date) && ($request->end_date <= $request->start_date)) {
                throw new \Exception("Due Date should not less than Start Date");
            }

            if (empty($request->start_date) && !empty($task->{Task::start_date})) {
                $endDateTs   = Carbon::createFromTimestamp($request->end_date);
                $startDateTs = Carbon::parse($task->{Task::start_date});


                if (($endDateTs->isSameDay($startDateTs) && $endDateTs->isSameMonth($startDateTs) && $endDateTs->isSameYear($startDateTs)) &&
                    ($endDateTs->diffInMinutes($startDateTs) <= 20)) {
                    throw new \Exception("Due Date should be 20 minutes greater than start date");
                }

                if ($request->end_date <= $startDateTs->timestamp) {
                    throw new \Exception("Due Date should not less than Start Date");
                }
            }

            if (!empty($request->start_date)) {
                $endDateTs   = Carbon::createFromTimestamp($request->end_date);
                $startDateTs = Carbon::createFromTimestamp($request->start_date);


                if (($endDateTs->isSameDay($startDateTs) && $endDateTs->isSameMonth($startDateTs) && $endDateTs->isSameYear($startDateTs)) &&
                    ($endDateTs->diffInMinutes($startDateTs) <= 20)) {
                    throw new \Exception("Due Date should be 20 minutes greater than start date");
                }
            }



            $task->{Task::end_date} = $request->end_date;
        }

        if ($request->repeat)
            $task->{Task::repeat} = $request->repeat;

        if ($request->responsible_person_id)
            $task->{Task::responsible_person_id} = $request->responsible_person_id;

        if ($request->responsible_person_change_time)
            $task->{Task::responsible_person_time_change} = $request->responsible_person_change_time;

        if ($request->responsible_person) {
            $assignedUser = $this->checkUser($request->responsible_person);
            $task->{Task::responsible_person_id}   = $assignedUser->id;
        }

        if ($request->reminder) {
            /*$endDate = null;
            if (!empty($request->{Task::end_date})) {
                $endDate   = Carbon::createFromTimestamp($request->{Task::end_date});
                if ($request->reminder > $request->{Task::end_date}) {
                    throw new \Exception("reminder should not greater than due date");
                }

            } else if (!empty($task->{Task::end_date})) {
                $endDate   = Carbon::parse($task->{Task::end_date});
                if ($request->reminder > $endDate->timestamp) {
                    throw new \Exception("reminder should not greater than due date");
                }
            }

            if (!empty($endDate)) {
                $reminder = Carbon::createFromTimestamp($request->reminder);

                if ($reminder->lessThan(Carbon::now())) {
                    throw new \Exception("cant set reminder in past time");
                }
            }*/

            $task->{Task::reminder}  = $request->reminder;
        }

        if ($request->parent_task) {
            $assignedTask = $this->checkTask($request->parent_task);
            $task->{Task::parent_task_id}   = $assignedTask->id;
        }

        if ($request->has('priority')) {
            $task->{Task::priority}      = $request->priority;
        }

        if ($request->has('favourite')) {
            $task->{Task::favourite}     = $request->favourite;
        }

        if ($request->is_template) {
            $task->{Task::is_template}   = $request->is_template;
        }


        if ($request->task_status) {
            $taskStatus = $this->getTaskStatus($request->task_status);
            $task->{Task::task_status_id} = $taskStatus->id;
            $this->addTaskStatusLog($task, $user);
        }

        return $task;

    }

    /**
     * @param $task
     * @param $user
     */
    public function addTaskStatusLog($task, $user)
    {
        $taskStatusLog = TaskStatusLog::where(TaskStatusLog::task_id, $task->id)
            ->latest()->first();

        if ((!$taskStatusLog) ||
            ($taskStatusLog->{TaskStatusLog::current_status_id} != $task->{Task::task_status_id})) {
            TaskStatusLog::create([
                TaskStatusLog::task_id => $task->id,
                TaskStatusLog::user_id => $user->id,
                TaskStatusLog::previous_status_id => ($taskStatusLog)? $taskStatusLog->{TaskStatusLog::current_status_id} : NULL,
                TaskStatusLog::status_log_time    => Carbon::now(),
                TaskStatusLog::current_status_id  => $task->{Task::task_status_id}
            ]);
        }

        return;
    }

    /**
     * @param $collectionObj
     * @param $task
     * @param $user
     * @return mixed
     */
    public function addCheckList($collectionObj, $task, $user)
    {
        return $collectionObj->map(function ($item) use ($task, $user) {
            return array(
                TaskChecklists::slug => Utilities::getUniqueId(),
                TaskChecklists::description => $item['description'],
                TaskChecklists::task_id     => $task->id,
                TaskChecklists::user_id     => $user->id,
                TaskChecklists::checklist_status => $item['checklistStatus']
            );
        })->all();
    }

    /**
     * update checklist
     * @param $collectionObj
     * @param $task
     * @param $user
     */
    public function updateCheckList($collectionObj, $task, $user)
    {
        $checklistArr = ($collectionObj->pluck('checklist_id')->filter());
        TaskChecklists::whereNotIn(TaskChecklists::slug, array_flatten($checklistArr))
            ->where(TaskChecklists::task_id, $task->id)->delete();

        $collectionObj->each(function ($item) use ($task, $user) {
            TaskChecklists::updateOrCreate(
                [
                    TaskChecklists::slug => !empty($item['checklist_id']) ? $item['checklist_id'] : Utilities::getUniqueId()
                ],
                [
                    TaskChecklists::slug        => !empty($item['checklist_id']) ? $item['checklist_id'] : Utilities::getUniqueId(),
                    TaskChecklists::description => $item['description'],
                    TaskChecklists::task_id     => $task->id,
                    TaskChecklists::user_id     => $user->id,
                    TaskChecklists::checklist_status => $item['is_checked']
                ]
            );

        });
    }

    /**
     * Update participants for update task
     * @param $collectionObj
     * @param $task
     */
    public function updateParticipants($collectionObj, $task)
    {
        $diffParticipants = array();
        $taskParticipants = DB::table(TaskParticipants::table)
            ->join(User::table, User::table.'.id', '=', TaskParticipants::table.'.'. TaskParticipants::user_id)
            ->select(User::table.'.'.User::slug)
            ->where(TaskParticipants::task_id, $task->id)
            ->get()
            ->pluck(User::slug);

        $diffInsertValues = $collectionObj->diffAssoc($taskParticipants);
        $diffDeleteValues = $taskParticipants->diffAssoc($collectionObj);

        $diffInsertValues->each(function ($diffInsert) use ($task) {
            $user = $this->checkUser($diffInsert);
            TaskParticipants::create([
                TaskParticipants::slug => Utilities::getUniqueId(),
                TaskParticipants::task_id => $task->id,
                TaskParticipants::user_id => $user->id,
            ]);
        });

        $diffDeleteValues->each(function ($diffDelete) use ($task) {
            $user = $this->checkUser($diffDelete);
            $findParticipants = TaskParticipants::where(TaskParticipants::user_id, $user->id)
                ->where(TaskParticipants::task_id, $task->id)->first();
            if ($findParticipants)
                $findParticipants->delete();
        });
    }

    /**
     * get organization users
     * @return array
     */
    public function getOrganizationUsers(Request $request)
    {
        $s3BasePath = env('S3_PATH');
        $status = ResponseStatus::OK;
        //selects only employees under an organization

        $orgId       = $this->getOrganization($request->org_slug);

        $orgEmployees = DB::table(OrgEmployee::table)
            ->select(
                OrgEmployee::table. '.' .OrgEmployee::name,
                OrgEmployee::table. '.' .OrgEmployee::slug.' as employeeSlug',
                User::table. '.' .User::slug,
                DB::raw('concat("'.$s3BasePath.'",employeeImage.'. UserProfile::image_path .') as employeeImage')
            )
            ->join(User::table, User::table. '.id', '=', OrgEmployee::table. '.'. OrgEmployee::user_id)
            ->leftjoin(UserProfile::table. ' as employeeImage', User::table. '.id', '=', 'employeeImage.' .UserProfile::user_id)
            ->where(OrgEmployee::table. '.'. OrgEmployee::org_id, $orgId->id)
            ->orderBy(OrgEmployee::table. '.' .OrgEmployee::name, 'asc');

        if ($request->q) {
            $query = request()->q;
            $orgEmployees->Where(OrgEmployee::table. '.'. OrgEmployee::name, 'like', "%{$query}%");
        }

        if (!$orgEmployees->exists()) {
            $status = ResponseStatus::NOT_FOUND;
        }

        return $this->content = array(
            'data'   => $orgEmployees->get(),
            'code'   => Response::HTTP_OK,
            'status' => $status
        );
    }

    /**
     * parent Task Lists for create task
     * @return array
     */
    public function getParentTaskLists()
    {
        $me = Auth::user();
        $status = ResponseStatus::OK;

        $tasks = Task::select(Task::table. '.id', Task::table. '.' .Task::slug, Task::table. '.' .Task::title)
            ->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
            ->where(function ($query) use ($me) {
                $query->orWhere(Task::table . '.' .Task::creator_user_id, $me->id)
                    ->orWhere(Task::table . '.' .Task::responsible_person_id, $me->id)
                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $me->id);
            })
            ->where(Task::table. '.'. Task::is_template, false)
            ->where(Task::table . '.' .Task::archive, false);

        if (request()->has('q')) {
            $query = request()->q;
            $tasks->Where(Task::table. '.' .Task::title, 'like', "%{$query}%");
        }

        $tasks->groupBy(Task::table. '.id');
        $response = $tasks->get();

        if ($response->isEmpty()) {
            $status = ResponseStatus::NOT_FOUND;
        }

        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => $status
        );
    }

    /**
     * Add participants
     * @param $collectionObj
     * @param $task
     * @param $user
     * @return mixed
     */
    public function addParticipants($collectionObj, $task, $user)
    {
        return $collectionObj->map(function ($item) use ($task, $user) {
            $user = $this->checkUser($item);

            return array(
                TaskParticipants::slug => Utilities::getUniqueId(),
                TaskChecklists::task_id     => $task->id,
                TaskChecklists::user_id     => $user->id
            );
        })->all();
    }

    /**
     * adding subtasks
     * @param $collectionObj
     * @param $task
     * @param $user
     */
    public function addSubTask($collectionObj, $task, $user)
    {
        $subtasks = Task::whereNotIn(Task::slug, array_flatten($collectionObj))
            ->where(Task::parent_task_id, $task->id)
            ->update([Task::parent_task_id => NULL]);

        $collectionObj->each(function ($item) use ($task, $user) {
            $taskCheck = $this->checkTask($item);
            $taskCheck->{Task::parent_task_id} = $task->id;
            $taskCheck->save();
        });
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function checkUser($slug)
    {
        return User::where(User::slug, $slug)->firstOrFail();
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function checkTask($slug)
    {
        return Task::where(Task::slug, $slug)->firstOrFail();
    }

    private function getOrganization($slug)
    {
        return Organization::where(Organization::slug, $slug)
            ->select('id')
            ->firstOrFail();
    }

    private function getTaskStatus($status)
    {
        switch ($status) {
            case 'start' :
                $taskStatus = TaskStatus::ongoing;break;
            case 'pause' :
                $taskStatus = TaskStatus::pause;break;
            case 'complete' :
                $taskStatus = TaskStatus::completed_waiting_approval;break;
            default:
                $taskStatus = TaskStatus::active;break;
        }


                    //dd($taskStatus);
        return TaskStatus::where(TaskStatus::title, $taskStatus)->firstOrFail();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function deleteTask(Request $request)
    {
        $loggedUser = Auth::user();

        try {
            DB::beginTransaction();
            $task = Task::where(Task::slug, $request->task)->first();

            if (!$task) {
                throw new \Exception("Invalid Task", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($loggedUser->id != $task->{Task::creator_user_id}) {
                throw new \Exception("You don't have permission to delete task!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $orgSlug = DB::table(Organization::table)->select(Organization::slug)
                ->where('id', $task->{Task::org_id})->pluck(Organization::slug)->first();

            $taskFile = DB::table(TaskFile::table)->where(TaskFile::task_id, $task->id)->exists();

            if ($taskFile) {
                $path = "{$orgSlug}/task/{$task->slug}/";

                //delete from s3
                $fileUpload = new FileUpload;
                $fileUpload->deleteDir($path);
            }

            $task->delete();

            DB::commit();

            $this->content['data'] = "Task Deleted Successfully!";
            $this->content['code'] = Response::HTTP_OK;
            return $this->content;

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    /**
     * @TODO - delete file from s3
     * @param Request $request
     * @return array
     */
    public function taskBulkDelete(createRequestBulkDelete $request)
    {
        DB::beginTransaction();
        try {
            $loggedUser = Auth::user();

            $org = DB::table(Organization::table)->select('id')
                ->where(Organization::slug, $request->orgSlug)->first();

            if (!$org) {
                throw new \Exception("Invalid Organization", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $tasksUserNotIn = Task::whereIn(Task::table. '.' .Task::slug, $request->taskSlugs)
                ->where(Task::table. '.' .Task::creator_user_id, '!=', $loggedUser->id)->exists();

            if ($tasksUserNotIn) {
                throw new \Exception("Only Creator has the permission to delete", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $tasks = Task::whereIn(Task::table. '.' .Task::slug, $request->taskSlugs)
                ->where(Task::table. '.' .Task::creator_user_id, $loggedUser->id)->delete();

            DB::commit();

            $this->content['data']   = array('msg' => 'Task Deleted Successfully!');
            $this->content['code']   = Response::HTTP_OK;
            $this->content['status'] = ResponseStatus::OK;
            return $this->content;

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    public function getAllTaskStatus()
    {
        $status   = ResponseStatus::OK;
        $response = DB::table(TaskStatus::table)
            ->select(
                TaskStatus::table. '.' .TaskStatus::slug,
                TaskStatus::table. '.' .TaskStatus::title,
                TaskStatus::table. '.' .TaskStatus::display_name
            )->get();

        if ($response->isEmpty())
            $status = ResponseStatus::NOT_FOUND;

        $this->content['data']   = $response;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = $status;
        return $this->content;
    }

    public function addToArchive(Request $request)
    {
        $org = DB::table(Organization::table)->where(Organization::slug, $request->orgSlug)->select('id')
            ->first();

        $task = Task::where(Task::slug, $request->taskSlug)
            ->first();

        DB::beginTransaction();
        try {
            if (!$org) {
                throw new \Exception("Invalid Organisation");
            }

            if (!$task) {
                throw new \Exception("Invalid Task");
            }

            $task->update([Task::archive => true]);

            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Task moved to archive!'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    public function favOrPriorityMultipleUpdate(Request $request)
    {
        $tasks = DB::table(Task::table)->whereIn(Task::slug, $request->taskSlugs);

        DB::beginTransaction();
        try {

            if (!in_array($request->key, ['favourite', 'priority'])) {
                throw new \Exception("Invalid key");
            }

            if ($request->key == 'favourite') {
                //DB::enableQueryLog();
                $tasks->update([Task::favourite => $request->value]);
                //dd(DB::getQueryLog());
            } else if ($request->key == 'priority') {
                $tasks->update([Task::priority => $request->value]);
            }

            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Task '. $request->key . ' updated'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }

    public function changeChecklistStatus(Request $request)
    {
        $loggedUser = Auth::user();




        try {
            DB::beginTransaction();
            $taskChecklist = $taskChecklistUpdate = TaskChecklists::where(TaskChecklists::table. '.' .TaskChecklists::slug, $request->checklistSlug)
                ->join(Task::table, Task::table. '.id', '=', TaskChecklists::table. '.' .TaskChecklists::task_id)
                ->select(
                    Task::table. '.' .Task::responsible_person_id,
                    Task::table. '.' .Task::creator_user_id,
                    Task::table. '.' .Task::approver_user_id
                )->first();

            //$taskChecklist = $taskChecklist->first();
            if (!$taskChecklist) {
                throw new \Exception("Invalid Checklist!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // logged user is aprover of the task
            if (($taskChecklist->{Task::approver_user_id} == $loggedUser->id) &&
                (($taskChecklist->{Task::responsible_person_id} != $loggedUser->id) &&
                    ($taskChecklist->{Task::creator_user_id} != $loggedUser->id))) {
                throw new \Exception("Approver has no permission to check", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $taskChecklist = $taskChecklistUpdate = TaskChecklists::where(TaskChecklists::table. '.' .TaskChecklists::slug, $request->checklistSlug)->first();

            $taskChecklist->{TaskChecklists::checklist_status} = $request->checklistStatus;
            $taskChecklist->{TaskChecklists::user_id} = $loggedUser->id;
            $taskChecklist->save();

            DB::commit();

            return $this->content = array(
                'data'   => array('msg' => 'Checklist status updated!'),
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }
    }


    /**********************************Task User Buttons*************************************/


    /**
     * @param $task
     * @param $me
     * @return array
     */
    public function getUserButtons($task, $me, $statusArray)
    {
        $buttonArrays = ['start' => false, 'pause' => false, 'complete' => false, 'accepted' => false,
            'returnTask' => false
        ];

        if (($task->{Task::creator_user_id} == $me->id) && ($task->{Task::responsible_person_id} == $me->id) && ($task->{Task::approver_user_id} == $me->id)) {
            if (in_array($task->{Task::task_status_id}, [$statusArray[TaskStatus::active],
                $statusArray[TaskStatus::overdue],  $statusArray[TaskStatus::pause]])) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if (($task->{Task::creator_user_id} == $me->id) && ($task->{Task::approver_user_id} == $me->id)) {
            if (in_array($task->{Task::task_status_id}, [$statusArray[TaskStatus::active],
                $statusArray[TaskStatus::overdue],  $statusArray[TaskStatus::pause]])) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if (($task->{Task::creator_user_id} == $me->id) && ($task->{Task::responsible_person_id} == $me->id)) {
            if (in_array($task->{Task::task_status_id}, [$statusArray[TaskStatus::active],
                $statusArray[TaskStatus::overdue],  $statusArray[TaskStatus::pause]])) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if (($task->{Task::approver_user_id} == $me->id) && ($task->{Task::responsible_person_id} == $me->id)) {
            if (in_array($task->{Task::task_status_id}, [$statusArray[TaskStatus::active],
                $statusArray[TaskStatus::overdue],  $statusArray[TaskStatus::pause]])) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
                $buttonArrays = $this->setButtonOngoingStatus($buttonArrays);
            }
        } else if ($task->{Task::approver_user_id} == $me->id) {
            if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
                $buttonArrays['complete'] = true;
            }
        } else if ($task->{Task::responsible_person_id} == $me->id) {
            if (in_array($task->{Task::task_status_id}, [$statusArray[TaskStatus::active],
                $statusArray[TaskStatus::overdue],  $statusArray[TaskStatus::pause]])) {
                $buttonArrays = $this->setButtonActiveStatus($buttonArrays);
            } else if ($task->{Task::task_status_id} == $statusArray[TaskStatus::ongoing]) {
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

}