<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 24/04/18
 * Time: 03:39 PM
 */

namespace Modules\TaskManagement\Repositories\Task;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\TaskManagement\Entities\TaskFilterCreatedBy;
use Modules\TaskManagement\Entities\TaskFilterParticipants;
use Modules\TaskManagement\Entities\TaskFilterResponsiblePersons;
use Modules\TaskManagement\Entities\TaskFilterTaskStatus;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\TaskManagement\Repositories\TaskFilterRepositoryInterface;
use Modules\TaskManagement\Transformers\TaskFilterListCollection;
use Modules\UserManagement\Entities\User;

class TaskFilterRepository implements TaskFilterRepositoryInterface
{

    protected $content;
    public function __construct()
    {
        $this->content = array();
    }

    /**
     * @param Request $request
     * @return array
     */

    public function createOrUpdateFilter($request, $action = 'create')
    {
        $user       = Auth::user();


        if ($action == 'create') {
            $orgid      = $this->getOrganizationId($request->orgSlug);
            $taskFilter = new TaskFilter;
            $taskFilter->{TaskFilter::slug}    = Utilities::getUniqueId();
            $taskFilter->{TaskFilter::org_id}  = $orgid->id;

        } else {
            $taskFilter = TaskFilter::where(TaskFilter::slug, $request->filterSlug)
                ->firstOrFail();
        }

        $taskFilter->{TaskFilter::title}   = $request->filterName;
        $taskFilter->{TaskFilter::user_id} = $user->id;

        if ($request->has('taskStatus')) {
            $taskFilter->{TaskFilter::task_status} = !empty($request->taskStatus) ? true : false;
        }


        if ($request->has('priority') && ($request->priority != NULL)) {
            $taskFilter->{TaskFilter::priority} = $request->priority;
        }


        if ($request->has('favourite') && ($request->favourite != NULL))
            $taskFilter->{TaskFilter::favourite} = $request->favourite;

        if ($request->has('withAttachement') && ($request->withAttachement != NULL))
            $taskFilter->{TaskFilter::is_attachment} = $request->withAttachement;

        if ($request->has('includesSubtask') && ($request->includesSubtask != NULL))
            $taskFilter->{TaskFilter::is_subtask} = $request->includesSubtask;

        if ($request->has('includesChecklist') && ($request->includesChecklist != NULL))
            $taskFilter->{TaskFilter::is_checklist} = $request->includesChecklist;

        if ($request->has('dueDate') && $request->dueDate)
            $taskFilter->{TaskFilter::due_date} = $request->dueDate;

        if ($request->has('startDate') && $request->startDate)
            $taskFilter->{TaskFilter::start_date} = $request->startDate;

        if ($request->has('finishedOn') && $request->finishedOn)
            $taskFilter->{TaskFilter::finished_on} = $request->finishedOn;

        if ($request->has('participants')) {
            $taskFilter->{TaskFilter::participant} = !empty($request->participants) ? true : false;
        }

        if ($request->has('responsiblePerson')) {
            $taskFilter->{TaskFilter::responsible_person} = !empty($request->responsiblePerson) ? true : false;
        }

        if ($request->has('createdBy')) {
            $taskFilter->{TaskFilter::created_by} = !empty($request->createdBy) ? true : false;
        }

        return $taskFilter;
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

    /**
     * @param $collectionObj
     * @param $taskFilter
     */
    public function saveTaskStatus($collectionObj, $taskFilterId)
    {
        $collectionObj->map(function ($item) use ($taskFilterId) {
            TaskFilterTaskStatus::create([
                    TaskFilterTaskStatus::task_status_id => $item,
                    TaskFilterTaskStatus::task_filter_id => $taskFilterId
                ]
            );
        });
    }

    /**
     * @param $collectionObj
     * @param $taskFilter
     */
    public function saveFilterTaskParticipants($collectionObj, $taskFilterId)
    {
        $collectionObj->map(function ($item) use ($taskFilterId) {
            TaskFilterParticipants::create([
                    TaskFilterParticipants::participant_id => $item,
                    TaskFilterParticipants::task_filter_id => $taskFilterId
                ]
            );
        });
    }

    /**
     * @param $collectionObj
     * @param $taskFilter
     */
    public function saveFilterTaskResponsiblePerson($collectionObj, $taskFilterId)
    {
        $collectionObj->map(function ($item) use ($taskFilterId) {
            TaskFilterResponsiblePersons::create([
                    TaskFilterResponsiblePersons::responsible_person_id => $item,
                    TaskFilterResponsiblePersons::task_filter_id => $taskFilterId
                ]
            );
        });
    }

    /**
     * @param $collectionObj
     * @param $taskFilter
     */
    public function saveFilterTaskCreatedBy($collectionObj, $taskFilterId)
    {
        $collectionObj->map(function ($item) use ($taskFilterId) {
            TaskFilterCreatedBy::create([
                    TaskFilterCreatedBy::created_by_id  => $item,
                    TaskFilterCreatedBy::task_filter_id => $taskFilterId
                ]
            );
        });
    }

    public function deleteFilter(Request $request)
    {

        $taskFilter = TaskFilter::where(TaskFilter::slug, $request->task_filter)->firstOrFail();
        $taskFilter->delete();

        $this->content['data'] = "Task Deleted Successfully!";
        $this->content['code'] = Response::HTTP_OK;
        return $this->content;
    }

    public function editFilter(Request $request)
    {
        $taskFilter = TaskFilter::where(TaskFilter::table. '.' .TaskFilter::slug, $request->task_filter)->firstOrFail();

        $taskFilter->participants        = [];
        $taskFilter->created_users       = [];
        $taskFilter->responsible_persons = [];
        $taskFilter->task_filter_status  = [];


        if ($taskFilter->{TaskFilter::task_status}) {
            $taskFilter->task_filter_status = $this->mapTaskStatusFilter($taskFilter);
        }

        if ($taskFilter->{TaskFilter::participant}) {
            $taskFilter->participants = $this->mapParticipantsFilter($taskFilter);
        }

        if ($taskFilter->{TaskFilter::created_by}) {
            $taskFilter->created_users = $this->mapCreatedByFilter($taskFilter);
        }

        if ($taskFilter->{TaskFilter::responsible_person}) {
            $taskFilter->responsible_persons = $this->mapResponsiblePersonFilter($taskFilter);
        }

        $response = new TaskFilterListCollection($taskFilter);

        $this->content['data']   = $response;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = ResponseStatus::OK;
        return $this->content;
    }

    public function mapTaskStatusFilter($taskFilter)
    {
        $taskStatusIdArr  = $this->getTaskFilterStatus($taskFilter->id)->pluck(TaskFilterTaskStatus::task_status_id);
        $taskStatusArrays = $this->getTaskStatuses($taskStatusIdArr);
        return $this->mapTaskFilterWithStatus($taskStatusArrays);
    }

    public function mapParticipantsFilter($taskFilter)
    {
        $participantIdArr = $this->getTaskFilterParticipants($taskFilter->id)->pluck(TaskFilterParticipants::participant_id);
        $userArrays = $this->getUsers($participantIdArr);
        return $this->mapTaskFilterWithUser($userArrays);
    }

    public function mapCreatedByFilter($taskFilter)
    {
        $createdByArr = $this->getTaskFilterCreatedBy($taskFilter->id)->pluck(TaskFilterCreatedBy::created_by_id);
        $userArrays = $this->getUsers($createdByArr);
        return $this->mapTaskFilterWithUser($userArrays);
    }

    public function mapResponsiblePersonFilter($taskFilter)
    {
        $responsibleArr = $this->getTaskResponsiblePersons($taskFilter->id)->pluck(TaskFilterResponsiblePersons::responsible_person_id);
        $userArrays = $this->getUsers($responsibleArr);
        return $this->mapTaskFilterWithUser($userArrays);
    }

    /**
     * map with users
     * @param $userArrays
     * @return mixed
     */
    public function mapTaskFilterWithUser($userArrays)
    {
        return $userArrays->map(function ($user) {
            return [
                'slug' => $user->{User::slug},
                'name' => $user->{User::name}
            ];
        });
    }

    /**
     * map with task Statuses
     * @param $statusArrays
     * @return mixed
     */
        public function mapTaskFilterWithStatus($statusArrays)
        {
            return $statusArrays->map(function ($status) {
                return [
                    'slug' => $status->{TaskStatus::slug},
                    'name' => $status->{TaskStatus::title}
                ];
            });
        }

    /**
     * get participants from task filter participants
     * @param $taskFilterId
     * @return mixed
     */
    public function getTaskFilterParticipants($taskFilterId)
    {
        return TaskFilterParticipants::select(TaskFilterParticipants::participant_id)
            ->where(TaskFilterParticipants::task_filter_id, $taskFilterId)
            ->get();
    }

    /**
     * get task filter created by user ids
     * @param $taskFilterId
     * @return mixed
     */
    public function getTaskFilterCreatedBy($taskFilterId)
    {
        return TaskFilterCreatedBy::select(TaskFilterCreatedBy::created_by_id)
            ->where(TaskFilterCreatedBy::task_filter_id, $taskFilterId)
            ->get();
    }

    /**
     * @param $taskFilterId
     * @return mixed
     */
    public function getTaskResponsiblePersons($taskFilterId)
    {
        return TaskFilterResponsiblePersons::select(TaskFilterResponsiblePersons::responsible_person_id)
            ->where(TaskFilterResponsiblePersons::task_filter_id, $taskFilterId)
            ->get();
    }

    public function getTaskFilterStatus($taskFilterId)
    {
        return TaskFilterTaskStatus::select(TaskFilterTaskStatus::task_status_id)
            ->where(TaskFilterTaskStatus::task_filter_id, $taskFilterId)
            ->get();
    }

    public function getUsers($ids)
    {
        return User::select(User::slug, User::name)->whereIn('id', $ids)->get();
    }

    /**
     * get Task statuses from arrays
     * @param $ids
     * @return mixed
     */
    public function getTaskStatuses($ids)
    {
        return TaskStatus::select(TaskStatus::slug, TaskStatus::title)->whereIn('id', $ids)->get();
    }

    public function getTaskStatus($slug)
    {
        return TaskStatus::where(TaskStatus::slug, $slug)->firstOrFail();
    }

    public function getUser($slug)
    {
        return User::where(User::slug, $slug)->firstOrFail();
    }

    private function getRequestCollectionObject($request)
    {
        return collect($request);
    }

    private function getOrganizationId($slug)
    {
        return Organization::select(Organization::table. '.id')->where(Organization::slug, $slug)
            ->firstOrFail();
    }

    public function listFilters(Request $request)
    {
        $status = ResponseStatus::OK;
        $me     = Auth::user();
        $orgId  = $this->getOrganizationId($request->org_slug);

        $taskFilters = TaskFilter::select(TaskFilter::table. '.' .TaskFilter::slug,
            TaskFilter::table. '.' .TaskFilter::title)
            ->where(TaskFilter::table. '.' .TaskFilter::user_id, $me->id)
            ->where(TaskFilter::table. '.' .TaskFilter::org_id, $orgId->id)
            ->get();

        if ($taskFilters->isEmpty())
            $status = ResponseStatus::NOT_FOUND;

        $this->content['data']   = $taskFilters;
        $this->content['code']   = Response::HTTP_OK;
        $this->content['status'] = $status;
        return $this->content;
    }

    /**
     * Create Or Update Filter main Functionality
     * @param Request $request
     * @param $action (create, update)
     * @return array
     */
    public function createFilter(Request $request, $action)
    {
        DB::beginTransaction();

        try {

            $taskFilter = $this->createOrUpdateFilter($request, $action);
            $taskFilter->save();

            if ($request->has('taskStatus')) {
                $taskSlugArr = $this->parseSlug($request->taskStatus);
                $this->processTaskStatus($taskFilter->id, $taskSlugArr);
            }

            if ($request->has('participants')) {
                $participantSlugArr = $this->parseSlug($request->participants);
                $this->processParticipants($taskFilter->id, $participantSlugArr);
            }

            if ($request->has('responsiblePerson')) {
                $responsiblePersonSlugArr = $this->parseSlug($request->responsiblePerson);
                $this->processResponsiblePerson($taskFilter->id, $responsiblePersonSlugArr);
            }

            if ($request->has('createdBy')) {
                $createdByArr = $this->parseSlug($request->createdBy);
                $this->processCreatedBy($taskFilter->id, $createdByArr);
            }

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_NOT_FOUND;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_NOT_FOUND;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => ($action == 'create') ? 'Task Filter Created!' : 'Task Filter Updated!'),
            'code'   => ($action == 'create') ? Response::HTTP_CREATED : Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function processTaskStatus($taskFilterId, $slugArrays)
    {
        $filterTaskStatuses = TaskFilterTaskStatus::select(TaskFilterTaskStatus::task_status_id)
            ->where(TaskFilterTaskStatus::task_filter_id, $taskFilterId)
            ->get()
            ->pluck(TaskFilterTaskStatus::task_status_id);

        $taskStatuses = TaskStatus::select(TaskStatus::table. '.id')
            ->whereIn(TaskStatus::table. '.' .TaskStatus::slug, $slugArrays)
            ->get()
            ->pluck('id');

        $diffDeleteValues = $filterTaskStatuses->diff($taskStatuses);
        $diffInsertValues = $taskStatuses->diff($filterTaskStatuses);

        if ($diffDeleteValues->isNotEmpty()) {
            TaskFilterTaskStatus::whereIn(TaskFilterTaskStatus::task_status_id, $diffDeleteValues->toArray())->delete();
        }

        $this->saveTaskStatus($diffInsertValues, $taskFilterId);
    }

    public function processParticipants($taskFilterId, $slugArrays)
    {
        $filterTaskParticipants = TaskFilterParticipants::select(TaskFilterParticipants::participant_id)
            ->where(TaskFilterParticipants::task_filter_id, $taskFilterId)
            ->get()
            ->pluck(TaskFilterParticipants::participant_id);

        $taskUsers = User::select(User::table. '.id')
            ->whereIn(User::table. '.' .User::slug, $slugArrays)
            ->get()
            ->pluck('id');

        $diffDeleteValues = $filterTaskParticipants->diff($taskUsers);
        $diffInsertValues = $taskUsers->diff($filterTaskParticipants);

        if ($diffDeleteValues->isNotEmpty()) {
            TaskFilterParticipants::whereIn(TaskFilterParticipants::participant_id, $diffDeleteValues->toArray())->delete();
        }

        $this->saveFilterTaskParticipants($diffInsertValues, $taskFilterId);
    }

    public function processResponsiblePerson($taskFilterId, $slugArrays)
    {
        $filterTaskRespnosible = TaskFilterResponsiblePersons::select(TaskFilterResponsiblePersons::responsible_person_id)
            ->where(TaskFilterResponsiblePersons::task_filter_id, $taskFilterId)
            ->get()
            ->pluck(TaskFilterResponsiblePersons::responsible_person_id);

        $taskUsers = User::select(User::table. '.id')
            ->whereIn(User::table. '.' .User::slug, $slugArrays)
            ->get()
            ->pluck('id');

        $diffDeleteValues = $filterTaskRespnosible->diff($taskUsers);
        $diffInsertValues = $taskUsers->diff($filterTaskRespnosible);


        if ($diffDeleteValues->isNotEmpty()) {
            TaskFilterResponsiblePersons::whereIn(TaskFilterResponsiblePersons::responsible_person_id, $diffDeleteValues->toArray())->delete();
        }

        $this->saveFilterTaskResponsiblePerson($diffInsertValues, $taskFilterId);
    }

    public function processCreatedBy($taskFilterId, $slugArrays)
    {
        $filterTaskCreatedBy = TaskFilterCreatedBy::select(TaskFilterCreatedBy::created_by_id)
            ->where(TaskFilterCreatedBy::task_filter_id, $taskFilterId)
            ->get()
            ->pluck(TaskFilterCreatedBy::created_by_id);

        $taskUsers = User::select(User::table. '.id')
            ->whereIn(User::table. '.' .User::slug, $slugArrays)
            ->get()
            ->pluck('id');

        $diffDeleteValues = $filterTaskCreatedBy->diff($taskUsers);
        $diffInsertValues = $taskUsers->diff($filterTaskCreatedBy);


        if ($diffDeleteValues->isNotEmpty()) {
            TaskFilterCreatedBy::whereIn(TaskFilterCreatedBy::created_by_id, $diffDeleteValues->toArray())->delete();
        }

        $this->saveFilterTaskCreatedBy($diffInsertValues, $taskFilterId);
    }

}

