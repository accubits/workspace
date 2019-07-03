<?php

namespace Modules\SocialModule\Repositories\Common;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Utilities\ResponseStatus;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\DB;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialActivityStreamTaskMap;
use Modules\SocialModule\Entities\SocialActivityStreamFormMap;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\UserManagement\Entities\UserProfile;
use Carbon\Carbon;

class CommonRepository implements CommonRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }
    
    private function getEmployeeBirthdays($orgSlug) {

        $curDate = date("Y-m-d");
        $curDatePlus30 = date("Y-m-d", strtotime("+30 days"));

        $empBirthDaysResultsObj = DB::table(OrgEmployee::table)
            ->join(User::table, User::table . ".id", '=', OrgEmployee::table . '.' . OrgEmployee::user_id)
            ->join(UserProfile::table, User::table . ".id", '=', UserProfile::table . '.' . UserProfile::user_id)
            ->join(Organization::table, Organization::table . ".id", '=', OrgEmployee::table . '.' . OrgEmployee::org_id)
            ->select(
                User::table.'.'.User::slug.' as userSlug',
                Organization::table.'.'.Organization::slug. ' as orgSlug',
                User::name,
                User::email,
                DB::raw("unix_timestamp(".UserProfile::table . '.'. UserProfile::birth_date.") AS birthDate"),
                DB::raw("DATE_ADD(".UserProfile::birth_date.", 
                INTERVAL YEAR(CURDATE())-YEAR(".UserProfile::birth_date.")
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(".UserProfile::birth_date."),1,0)
                YEAR) as upcomingBirthday"),
                DB::raw('concat("'. $this->s3BasePath.'", '.UserProfile::table.'.'. UserProfile::image_path.') as userImageUrl')
            )->where(Organization::table.'.'.Organization::slug,$orgSlug)

            ->whereBetween(DB::raw("DATE_ADD(".UserProfile::birth_date.", 
                INTERVAL YEAR(CURDATE())-YEAR(".UserProfile::birth_date.")
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(".UserProfile::birth_date."),1,0)
                YEAR)"), array($curDate , $curDatePlus30))

            ->orderBy('upcomingBirthday')
            ->get();
        return $empBirthDaysResultsObj;
    }
    
    public function getLookupId($title, $attribute, $value) {
        $lookUpId=null;
        if(!empty($value)){
            $lookUpObj = DB::table(SocialLookup::table)
                    ->where(SocialLookup::title, '=',$title)
                    ->where(SocialLookup::attribute, '=',$attribute)
                    ->where(SocialLookup::value, '=',$value)
                    ->first();
            if(empty($lookUpObj)){
                throw new \Exception("Invalid ".$title." ".$attribute);
            }
            $lookUpId = $lookUpObj->id;
        }
        return $lookUpId;
    }

    public function getUser($reqArr)
    {
        $userObj = DB::table(User::table)
            ->select(
                User::table. '.id'
            )
            ->where(User::slug, $reqArr['userSlug'])
            ->first();
        if(empty($userObj)){
            throw new \Exception("Invalid userSlug found!");
        }
        return $userObj;
    }
    


    ////////////////////

    public function setSocialActivityStreamForm($creatorUserObj,$organisationObj,$formObj, $toUserIdsCollectionObj, $note) {
        
        if(empty($creatorUserObj)){
            throw new \Exception(" creatorUserObj is empty");
        }
        if(empty($organisationObj)){
            throw new \Exception(" organisationObj is empty");
        }
        if(empty($formObj)){
            throw new \Exception(" formObj is empty");
        }
        if(empty($note)){
            throw new \Exception(" note is empty");
        }
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" toUserIdsCollectionObj is empty");
        }
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"form")
                ->first();
        
        if(empty($activityStreamTypeObj)){
            throw new \Exception(" 'form' activity stream type is missing.seed to fix");
        }

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $creatorUserObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterFormMap = DB::table(SocialActivityStreamFormMap::table)
                ->where(SocialActivityStreamFormMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamFormMap::form_master_id, '=',$formObj->id)
                ->first();
        
        if(empty($socialActivityStreamMasterFormMap)){
            //update old activity stream to hidden
            $this->updateOldFormActivityStream($formObj);
            $socialActivityStreamMasterFormMap = new SocialActivityStreamFormMap;
            $socialActivityStreamMasterFormMap->{SocialActivityStreamFormMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterFormMap->{SocialActivityStreamFormMap::form_master_id} = $formObj->id;
            $socialActivityStreamMasterFormMap->save();
        }
        //map all users under the form in  activity stream
        $this->setFormUserMapObjs($toUserIdsCollectionObj, $socialActivityStreamMaster);
        return $socialActivityStreamMaster;
    }
    
    private function updateOldFormActivityStream($formObj) {

        $socialActivityStreamMasterFormMappingCheckMap = DB::table(SocialActivityStreamFormMap::table)
        ->where(SocialActivityStreamFormMap::form_master_id, '=',$formObj->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterFormMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamFormMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }


    public function setFormUserMapObjs($toUserIdsCollectionObj, $socialActivityStreamMaster) {
        $toUserIdArr = array();
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" toUserIdsCollectionObj is empty");
        }
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" socialActivityStreamMaster is empty");
        }
        $toUserIdsCollectionObj->each(function ($item) use (&$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleActivityStreamFormUserMap($item, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }
    public function setSingleActivityStreamFormUserMap($userId, $socialActivityStreamMasterObj) {
        
        $socialActivityStreamUserObj = SocialActivityStreamUser::where(SocialActivityStreamUser::activity_stream_master_id, '=',$socialActivityStreamMasterObj->id)
                ->where(SocialActivityStreamUser::to_user_id, '=',$userId)
                ->first();
        if(empty($socialActivityStreamUserObj)){
            $socialActivityStreamUserObj = new SocialActivityStreamUser;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::to_user_id} = $userId;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::activity_stream_master_id} = $socialActivityStreamMasterObj->id;
            $socialActivityStreamUserObj->save();
        }
        return $socialActivityStreamUserObj->{SocialActivityStreamUser::to_user_id};
    }
        
////////////////////
    
    
    private function updateOldTaskActivityStream($taskObj) {

        $socialActivityStreamMasterFormMappingCheckMap = DB::table(SocialActivityStreamTaskMap::table)
        ->where(SocialActivityStreamTaskMap::task_id, '=',$taskObj->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterFormMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            $smId = $kitem->{SocialActivityStreamTaskMap::activity_stream_master_id};
            array_push($activityStreamMasterIDsArr, $smId);
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }
    
    public function setSocialActivityStreamTask($creatorUserObj,$organisationObj,$taskObj, $toUserIdsCollectionObj, $note) {
        
        if(empty($creatorUserObj)){
            throw new \Exception(" creatorUserObj is empty");
        }
        if(empty($organisationObj)){
            throw new \Exception(" organisationObj is empty");
        }
        if(empty($taskObj)){
            throw new \Exception(" taskObj is empty");
        }
        if(empty($note)){
            throw new \Exception(" note is empty");
        }
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" toUserIdsCollectionObj is empty");
        }
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"task")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $creatorUserObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        
        $socialActivityStreamMasterTaskMap = DB::table(SocialActivityStreamTaskMap::table)
                ->where(SocialActivityStreamTaskMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamTaskMap::task_id, '=',$taskObj->id)
                ->first();

        if(empty($socialActivityStreamMasterTaskMap)){
            //update old task activity streams
            $this->updateOldTaskActivityStream($taskObj);
            $socialActivityStreamMasterTaskMap = new SocialActivityStreamTaskMap;
            $socialActivityStreamMasterTaskMap->{SocialActivityStreamTaskMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterTaskMap->{SocialActivityStreamTaskMap::task_id} = $taskObj->id;
            $socialActivityStreamMasterTaskMap->save();
        }
        //map all users under the task activity stream
        $this->setTaskUserMapObjs($toUserIdsCollectionObj, $socialActivityStreamMaster);
        return $socialActivityStreamMaster;
    }

    public function setTaskUserMapObjs($toUserIdsCollectionObj, $socialActivityStreamMaster) {
        $toUserIdArr = array();
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" toUserIdsCollectionObj is empty");
        }
        if(empty($toUserIdsCollectionObj)){
            throw new \Exception(" socialActivityStreamMaster is empty");
        }
        $toUserIdsCollectionObj->each(function ($item) use (&$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleActivityStreamTaskUserMap($item, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }
    public function setSingleActivityStreamTaskUserMap($userId, $socialActivityStreamMasterObj) {
        
        $socialActivityStreamUserObj = SocialActivityStreamUser::where(SocialActivityStreamUser::activity_stream_master_id, '=',$socialActivityStreamMasterObj->id)
                ->where(SocialActivityStreamUser::to_user_id, '=',$userId)
                ->first();
        if(empty($socialActivityStreamUserObj)){
            $socialActivityStreamUserObj = new SocialActivityStreamUser;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::to_user_id} = $userId;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::activity_stream_master_id} = $socialActivityStreamMasterObj->id;
            $socialActivityStreamUserObj->save();
        }
        return $socialActivityStreamUserObj->{SocialActivityStreamUser::to_user_id};
    }

    ////////////////////


    /**
     * @param $request
     * @return array
     */
    public function fetchTaskWidget($request)
    {
        $this->content = array();
        $overview      = array();

        $loggedUser = Auth::user();
        try {

            if (empty($request->taskDateRange)) {
                $request->taskDateRange = 'sevenDays';
            }

            if (!in_array($request->taskDateRange, ['sevenDays', 'thirtyDays', 'oneyear'])) {
                throw new \Exception("Invalid taskDateRange", 422);
            }

            $organization = Organization::where(Organization::slug, $request->orgSlug)->first();

            if (!$organization) {
                throw new \Exception("No Organization Found", 422);
            }

            $orgSlug       = $request->orgSlug;
            $requestedDate = Carbon::now()->addDays(7);
            $today         = Carbon::today();

            if ($request->taskDateRange == 'sevenDays') {
                $requestedDate = Carbon::now()->addDays(7);
            } else if ($request->taskDateRange == 'thirtyDays') {
                $requestedDate = Carbon::now()->addDays(30);
            } else if ($request->taskDateRange == 'oneyear') {
                $requestedDate = Carbon::now()->addYears(1);
            }

            $taskStatusArr = $this->getStatusArray();
            $tasks = DB::table(Task::table)
                ->select(
                    Task::table. '.id',
                    Task::table. '.' .Task::slug,
                    Task::table. '.'. Task::title,
                    TaskStatus::table. '.'. TaskStatus::title. ' as task_status',
                    DB::raw("unix_timestamp(".Task::table . '.'.Task::end_date.") AS due_date"),
                    Task::table. '.'. Task::priority
                )
                ->join(Organization::table, Organization::table. '.id', '=', Task::table. '.'. Task::org_id)
                ->join(TaskStatus::table, TaskStatus::table. '.id', '=', Task::table. '.'. Task::task_status_id)
                ->where(function ($query) {
                    $query->whereNull(Task::table. '.'. Task::parent_task_id)
                        ->where(Task::table. '.'. Task::is_template, false);
                })
                ->where(function ($dateQuery) use ($requestedDate, $today, $taskStatusArr){
                    $dateQuery->whereDate(Task::table. '.' .Task::end_date, '<=', $requestedDate)
                        //->whereDate(Task::table. '.' .Task::end_date, '<=', $requestedDate)
                        ->orWhere(function ($dateQuery) use ($taskStatusArr){
                            $dateQuery->where(Task::table. '.' .Task::task_status_id, $taskStatusArr[TaskStatus::overdue]);
                        });
                })
                ->where(Task::table. '.'. Task::archive, false)
                ->where(Organization::table. '.' .Organization::slug, $orgSlug)
                ->groupBy(Task::table. '.id');


            //==========start overview============
            $tasksOverview  = clone $tasks;
            $tasksOverview  = $this->getTaskByUserCriteria($tasksOverview, $loggedUser);
            $overview = $this->tasksOverView($tasksOverview, $loggedUser);
            //==========end overview============

            $tasks  = $this->getTaskByUserCriteria($tasks, $loggedUser);
            //dd($tasks)


            //task not accept these statuses completed_approved, completed_waiting_approval
            $tasks = $tasks->where(function ($query)  use ($taskStatusArr) {
                $query->whereNotIn(Task::table. '.' .Task::task_status_id,
                    [$taskStatusArr[TaskStatus::completed_approved], $taskStatusArr[TaskStatus::completed_waiting_approval]]);
            });

            $tasks = $tasks->orderBy(DB::raw('CASE WHEN '. Task::table. '.' .Task::task_status_id. ' = '.$taskStatusArr[TaskStatus::overdue].
                ' THEN 0 ELSE 1 END'));
            $tasks = $tasks->orderBy(DB::raw('ABS(DATEDIFF('.Task::table. '.' .Task::end_date.', NOW()))'));
            $tasks = $tasks->orderBy(Task::table. '.' .Task::priority, true);
            /*DB::enableQueryLog();
            $tasks->get();
            dd(DB::getQueryLog());*/
            //employee birthdays
            $employeeBirthdays = $this->getEmployeeBirthdays($orgSlug);
            $status = ResponseStatus::OK;

            $data['overview']  = $overview;
            $data['task']      = $tasks->get();
            $data['birthdays'] = $employeeBirthdays;

            $this->content['data'] = $data;
            $this->content['code'] = Response::HTTP_OK;
            $this->content['status'] = $status;

            return $this->content;

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_NOT_FOUND;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }
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

    public function tasksOverView($tasks, $user)
    {
        $tasksCollection   = $tasks->get();

        $groupedTaskStatus = $tasksCollection->groupBy('task_status');


        $priority  = $tasksCollection->where(Task::priority, true)->count();

        $taskOverviewArr['active']    = (isset($groupedTaskStatus[TaskStatus::active])) ? $groupedTaskStatus[TaskStatus::active]->count() : 0;
        $taskOverviewArr['overdue']   = (isset($groupedTaskStatus[TaskStatus::overdue])) ? $groupedTaskStatus[TaskStatus::overdue]->count() : 0;
        $taskOverviewArr['priority']  = $priority;
        $taskOverviewArr['upcomingDeadline']  = $tasksCollection->where('due_date', '>=', Carbon::now()->subHours(12)->timestamp)
            ->where('due_date', '<=', Carbon::now()->timestamp)->count();

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
        //DB::enableQueryLog();
        return $tasks->leftJoin(TaskParticipants::table, TaskParticipants::table. '.' . TaskParticipants::task_id, '=',  Task::table. '.id')
            ->where(function ($query) use ($me) {
                $query
                    //->orWhere(Task::table . '.' .Task::creator_user_id, $me->id)
                    ->orWhere(Task::table . '.' .Task::approver_user_id, $me->id)
                    ->orWhere(Task::table . '.' .Task::responsible_person_id, $me->id)
                    ->orWhere(TaskParticipants::table . '.' .TaskParticipants::user_id, $me->id)
                    ->orWhere(Task::table. '.' .Task::is_to_allemployees, true);
            });
        //dd(DB::getQueryLog());
    }

}