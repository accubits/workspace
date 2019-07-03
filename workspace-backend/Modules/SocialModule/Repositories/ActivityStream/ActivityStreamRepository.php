<?php

namespace Modules\SocialModule\Repositories\ActivityStream;

use Modules\SocialModule\Repositories\ActivityStreamRepositoryInterface;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialMessageUserMap;
use Modules\SocialModule\Entities\SocialMessageComment;
use Modules\SocialModule\Entities\SocialMessageResponse;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\SocialModule\Entities\SocialAnnouncementUserMap;
use Modules\SocialModule\Entities\SocialAnnouncementComment;
use Modules\SocialModule\Entities\SocialAnnouncementCommentResponse;
use Modules\SocialModule\Entities\SocialAnnouncementResponse;
use Modules\SocialModule\Entities\SocialActivityStreamEventMap;
use Modules\SocialModule\Entities\SocialActivityStreamTaskMap;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\SocialModule\Entities\SocialEventComment;
use Modules\SocialModule\Entities\SocialEventCommentResponse;
use Modules\SocialModule\Entities\SocialEventResponse;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskComments;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\SocialModule\Entities\SocialPollGroup;
use Modules\SocialModule\Entities\SocialPoll;
use Modules\SocialModule\Entities\SocialPollInvitedUsers;
use Modules\SocialModule\Entities\SocialPollOptions;
use Modules\SocialModule\Entities\SocialPollUserAnswers;
use Modules\SocialModule\Entities\SocialPollComment;
use Modules\SocialModule\Entities\SocialPollCommentResponse;
use Modules\SocialModule\Entities\SocialPollResponse;
use Modules\SocialModule\Entities\SocialAppreciation;
use Modules\SocialModule\Entities\SocialAppreciationRecipientMap;
use Modules\SocialModule\Entities\SocialAppreciationUserMap;
use Modules\SocialModule\Entities\SocialActivityStreamAppreciationMap;
use Modules\SocialModule\Entities\SocialAppreciationComment;
use Modules\SocialModule\Entities\SocialAppreciationCommentResponse;
use Modules\SocialModule\Entities\SocialAppreciationResponse;

use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormPublishUsers;
use Modules\FormManagement\Entities\FormAnswerSheet;

use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\SocialModule\Entities\SocialActivityStreamMessageMap;
use Modules\SocialModule\Entities\SocialActivityStreamAnnouncementMap;
use Modules\SocialModule\Entities\SocialActivityStreamPollMap;
use Modules\SocialModule\Entities\SocialActivityStreamFormMap;
use Carbon\Carbon;

class ActivityStreamRepository implements ActivityStreamRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    /*
     * fetch activity stream
     */
    public function fetch($request)
    {
        $user = Auth::user();
        $supportedTabs = array('recent','top','task','event','message','announcement','poll','appreciation','form');
        $subTabArr = array(
            "msg" => array('recent','top','message'),
            "event" => array('recent','top','event'),
            "announcement" => array('recent','top','announcement'),
            "task" => array('recent','top','task'),
            "poll" => array('recent','top','poll'),
            "appreciation" => array('recent','top','appreciation'),
            "form" => array('recent','top','form')
        );
        
        $selectedTab=$request->tab;
        if(empty($selectedTab)){
            $selectedTab = 'recent';
        }
        if(!in_array($selectedTab, $supportedTabs)){
            $errmsg = "Invalid tab " .$selectedTab. " selected";
            return $this->ErrorResponse($errmsg, 422);
        }

        try {
            //DB::enableQueryLog();

            $organisationObj = DB::table(Organization::table)
            ->where(Organization::slug, '=',$request->orgSlug)
            ->select('id')
            ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }

            DB::statement("SET sql_mode = ''");
            //baseQuery
            $activityStreamsQueryBuilderObj = DB::table(SocialActivityStreamMaster::table)
            ->join(Organization::table, Organization::table . ".id", '=', SocialActivityStreamMaster::table . '.' . SocialActivityStreamMaster::org_id)    
            ->join(SocialActivityStreamType::table, SocialActivityStreamType::table . ".id", '=', SocialActivityStreamMaster::table . '.' . SocialActivityStreamMaster::activity_stream_type_id);

            if(in_array($selectedTab, $subTabArr['task'])){
                $activityStreamsQueryBuilderObj =$activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamTaskMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamTaskMap::table . '.' . SocialActivityStreamTaskMap::activity_stream_master_id)    
                ->leftJoin(Task::table, Task::table . ".id", '=', SocialActivityStreamTaskMap::table . '.' . SocialActivityStreamTaskMap::task_id)
                ->leftJoin(TaskStatus::table, Task::table . ".". Task::task_status_id, '=', TaskStatus::table . '.id')
                ->leftJoin(TaskParticipants::table, Task::table . ".id", '=', TaskParticipants::table . '.' . TaskParticipants::task_id)
                ->leftJoin(User::table.' as taskCreator', "taskCreator.id", '=', Task::table . '.' . Task::creator_user_id)
                ->leftJoin(UserProfile::table.' as taskCreatorProfile', "taskCreator.id", '=', 'taskCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as taskResponsiblePerson', "taskResponsiblePerson.id", '=', Task::table . '.' . Task::responsible_person_id)
                ->leftJoin(User::table.' as taskToUser', "taskToUser.id", '=', TaskParticipants::table . '.' . TaskParticipants::user_id)
                ->leftJoin(UserProfile::table.' as taskToUserProfile', "taskToUser.id", '=', 'taskToUserProfile.' . UserProfile::user_id);
            }
            
            if(in_array($selectedTab, $subTabArr['event'])){
                $activityStreamsQueryBuilderObj =$activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamEventMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamEventMap::table . '.' . SocialActivityStreamEventMap::activity_stream_master_id)    
                ->leftJoin(SocialEvent::table, SocialEvent::table . ".id", '=', SocialActivityStreamEventMap::table . '.' . SocialActivityStreamEventMap::event_id)
                ->leftJoin(SocialEventMember::table, SocialEvent::table . ".id", '=', SocialEventMember::table . '.' . SocialEventMember::social_event_id)
                ->leftJoin(User::table.' as eventCreator', "eventCreator.id", '=', SocialEvent::table . '.' . SocialEvent::creator_user_id)
                ->leftJoin(UserProfile::table.' as eventCreatorProfile', "eventCreator.id", '=', 'eventCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as eventToUser', "eventToUser.id", '=', SocialEventMember::table . '.' . SocialEventMember::user_id)
                ->leftJoin(UserProfile::table.' as eventToUserProfile', "eventToUser.id", '=', 'eventToUserProfile.' . UserProfile::user_id);
            }
            
            if(in_array($selectedTab, $subTabArr['announcement'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamAnnouncementMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamAnnouncementMap::table . '.' . SocialActivityStreamAnnouncementMap::activity_stream_master_id)    
                ->leftJoin(SocialAnnouncement::table, SocialAnnouncement::table . ".id", '=', SocialActivityStreamAnnouncementMap::table . '.' . SocialActivityStreamAnnouncementMap::annoucement_id)
                ->leftJoin(SocialAnnouncementUserMap::table, SocialAnnouncement::table . ".id", '=', SocialAnnouncementUserMap::table . '.' . SocialAnnouncementUserMap::social_announcement_id)
                ->leftJoin(User::table.' as ancCreator', "ancCreator.id", '=', SocialAnnouncement::table . '.' . SocialAnnouncement::creator_user_id)
                ->leftJoin(UserProfile::table.' as ancCreatorProfile', "ancCreator.id", '=', 'ancCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as ancToUser', "ancToUser.id", '=', SocialAnnouncementUserMap::table . '.' . SocialAnnouncementUserMap::user_id)
                ->leftJoin(UserProfile::table.' as ancToUserProfile', "ancToUser.id", '=', 'ancToUserProfile.' . UserProfile::user_id);
            }
            
            if(in_array($selectedTab, $subTabArr['msg'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamMessageMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamMessageMap::table . '.' . SocialActivityStreamMessageMap::activity_stream_master_id)    
                ->leftJoin(SocialMessage::table, SocialMessage::table . ".id", '=', SocialActivityStreamMessageMap::table . '.' . SocialActivityStreamMessageMap::message_id)
                ->leftJoin(SocialMessageUserMap::table, SocialMessage::table . ".id", '=', SocialMessageUserMap::table . '.' . SocialMessageUserMap::social_message_id)
                ->leftJoin(User::table.' as msgCreator', "msgCreator.id", '=', SocialMessage::table . '.' . SocialMessage::creator_user_id)
                ->leftJoin(UserProfile::table.' as msgCreatorProfile', "msgCreator.id", '=', 'msgCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table, User::table . ".id", '=', SocialMessageUserMap::table . '.' . SocialMessageUserMap::user_id)
                ->leftJoin(UserProfile::table.' as msgToUserProfile', User::table . ".id", '=', 'msgToUserProfile.' . UserProfile::user_id); 
            }

            if(in_array($selectedTab, $subTabArr['poll'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamPollMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamPollMap::table . '.' . SocialActivityStreamPollMap::activity_stream_master_id)    
                ->leftJoin(SocialPollGroup::table, SocialPollGroup::table . ".id", '=', SocialActivityStreamPollMap::table . '.' . SocialActivityStreamPollMap::poll_group_id)
                ->leftJoin(SocialLookup::table.' as pollStatusLookup', "pollStatusLookup.id", '=', SocialPollGroup::table . '.' . SocialPollGroup::status_id)
                ->leftJoin(SocialPollInvitedUsers::table, SocialPollGroup::table . ".id", '=', SocialPollInvitedUsers::table . '.' . SocialPollInvitedUsers::poll_group_id)
                ->leftJoin(User::table.' as pollCreator', "pollCreator.id", '=', SocialPollGroup::table . '.' . SocialPollGroup::creator_user_id)
                ->leftJoin(UserProfile::table.' as pollCreatorProfile', "pollCreator.id", '=', 'pollCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as pollToUser', "pollToUser.id", '=', SocialPollInvitedUsers::table . '.' . SocialPollInvitedUsers::user_id)
                ->leftJoin(UserProfile::table.' as pollToUserProfile', "pollToUser.id", '=', 'pollToUserProfile.' . UserProfile::user_id);
            }
            
            if(in_array($selectedTab, $subTabArr['appreciation'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamAppreciationMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamAppreciationMap::table . '.' . SocialActivityStreamAppreciationMap::activity_stream_master_id)    
                ->leftJoin(SocialAppreciation::table, SocialAppreciation::table . ".id", '=', SocialActivityStreamAppreciationMap::table . '.' . SocialActivityStreamAppreciationMap::appreciation_id)
                ->leftJoin(SocialLookup::table.' as aprStatusLookup', "aprStatusLookup.id", '=', SocialAppreciation::table . '.' . SocialAppreciation::status_id)
                ->leftJoin(SocialAppreciationUserMap::table, SocialAppreciation::table . ".id", '=', SocialAppreciationUserMap::table . '.' . SocialAppreciationUserMap::appreciation_id)
                ->leftJoin(User::table.' as aprCreator', "aprCreator.id", '=', SocialAppreciation::table . '.' . SocialAppreciation::creator_user_id)
                ->leftJoin(UserProfile::table.' as aprCreatorProfile', "aprCreator.id", '=', 'aprCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as aprToUser', "aprToUser.id", '=', SocialAppreciationUserMap::table . '.' . SocialAppreciationUserMap::user_id)
                ->leftJoin(UserProfile::table.' as aprToUserProfile', "aprToUser.id", '=', 'aprToUserProfile.' . UserProfile::user_id);
            }

            if(in_array($selectedTab, $subTabArr['form'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->leftJoin(SocialActivityStreamFormMap::table, SocialActivityStreamMaster::table . ".id", '=', SocialActivityStreamFormMap::table . '.' . SocialActivityStreamFormMap::activity_stream_master_id)    
                ->leftJoin(FormMaster::table, FormMaster::table . ".id", '=', SocialActivityStreamFormMap::table . '.' . SocialActivityStreamFormMap::form_master_id)
                ->leftJoin(FormPublishUsers::table, FormMaster::table . ".id", '=', FormPublishUsers::table . '.' . FormPublishUsers::form_master_id)
                ->leftJoin(User::table.' as FormCreator', "FormCreator.id", '=', FormMaster::table . '.' . FormMaster::creator_user_id)
                ->leftJoin(UserProfile::table.' as formCreatorProfile', "FormCreator.id", '=', 'formCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(User::table.' as FormSendUsers', "FormSendUsers.id", '=', FormPublishUsers::table . '.' . FormPublishUsers::user_id)
                ->leftJoin(UserProfile::table.' as FormSendUsersProfile', "FormSendUsers.id", '=', 'FormSendUsersProfile.' . UserProfile::user_id)
                ->leftJoin(FormAnswerSheet::table,
                        function($join) use($user){ // fetch submitted user answersheet if any
                                $join->on(FormMaster::table . ".id", '=', FormAnswerSheet::table . '.' . FormAnswerSheet::form_master_id);
                                $join->on(FormAnswerSheet::table . ".". FormAnswerSheet::is_discarded, '=', DB::raw('0'));
                                $join->on(FormAnswerSheet::table . ".". FormAnswerSheet::form_user_id, '=', DB::raw($user->id));
                        });
            }

            //base where
            $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj
                ->where(Organization::table .'.id', '=' , $organisationObj->id)
                ->where(SocialActivityStreamMaster::table .'.'.SocialActivityStreamMaster::is_hidden, '=' , FALSE);

            $completeActivityStreamIdSelectorQueryBuilderObj = clone $activityStreamsQueryBuilderObj;
            //get all matching activitystream id first
            $completeActivityStreamIdSelectorQueryBuilderObj = $this->getMainWhereQueryParts($completeActivityStreamIdSelectorQueryBuilderObj, $user, $selectedTab, $subTabArr);

            //group by activitystream id
            $completeActivityStreamIdSelectorQueryBuilderObj = $completeActivityStreamIdSelectorQueryBuilderObj->groupBy(SocialActivityStreamMaster::table . '.id');
            $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->groupBy(SocialActivityStreamMaster::table . '.id');

            //total
            $asmIdsCol = $completeActivityStreamIdSelectorQueryBuilderObj->select(SocialActivityStreamMaster::table . '.id AS activityStreamMasterId')->get();
            $asmCount = $asmIdsCol->count();

            //get all ActvitityStream info
            $activityStreamIdArr = $asmIdsCol->pluck('activityStreamMasterId')->toArray();
            $activityStreamsQueryBuilderObj->whereIn(SocialActivityStreamMaster::table . '.id',$activityStreamIdArr);

            //order by
            if($selectedTab=="top"){

                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj
                        ->orderBy(DB::raw("DATE(".SocialActivityStreamMaster::table . '.' . SocialActivityStreamMaster::CREATED_AT.")"), 'desc');

                
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj
                        ->orderBy(
                            DB::raw("( aprCommentsCount+aprResponsesCount"
                                    . "+announcementCommentsCount+announcementResponsesCount"
                                    . "+messageCommentsCount+messageResponsesCount"
                                    . "+pollCommentsCount+pollResponsesCount"
                                    . "+eventCommentsCount+eventResponsesCount )"),
                                'desc');

            } else {

                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj
                        ->orderBy(SocialActivityStreamMaster::table . '.' . SocialActivityStreamMaster::CREATED_AT, 'desc');
			}
            //basic select
            $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->select(
                Organization::table. '.' . Organization::slug. ' AS orgSlug',
                SocialActivityStreamType::table.'.'.SocialActivityStreamType::name.' AS type',
                SocialActivityStreamMaster::table.'.'.SocialActivityStreamMaster::note,
                SocialActivityStreamMaster::table . '.id AS activityStreamMasterId');

            //select all required fields
            $activityStreamsQueryBuilderObj = $this->getAllSelectors($activityStreamsQueryBuilderObj,$selectedTab, $subTabArr, $user);

            //pagination
            $activityStmColObj = $activityStreamsQueryBuilderObj  
             ->skip(Utilities::getParams()['offset']) //$request['offset']
             ->take(Utilities::getParams()['perPage']) //$request['perPage']
             ->get();

            //dd(DB::getQueryLog());

            //fetch pollgroupSlugs to fetch Additional Poll Details
            $pollGroupSlugsArr = $activityStmColObj->filter(function ($value, $key) {
                    return !empty($value->pollSlug);
               })->pluck('pollSlug')->toArray();

            $pollDetailsMap = $this->fetchPollDetailsMap($pollGroupSlugsArr, $user);

            //fetch aprSlugs to fetch Additional Appreciation Recepients
            $aprSlugsArr = $activityStmColObj->filter(function ($value, $key) {
                    return !empty($value->aprSlug);
               })->pluck('aprSlug')->toArray();

            $aprRecepientsMap = $this->fetchAppreciationReciepientsMap($aprSlugsArr);

            $streamData = array();
            $activityStmColObj->each(function($item) use(&$streamData, $pollDetailsMap, $aprRecepientsMap){
                array_push($streamData, $this->fetchSingleStreamItemObj($item, $pollDetailsMap, $aprRecepientsMap));
            });

            $paginatedData = Utilities::paginate($streamData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $asmCount)->toArray();

            $responseArr = array(
                "streamData"=>$paginatedData['data'],
                "total"=>$paginatedData['total'],
                "to"=>$paginatedData['to'],
                "from"=>$paginatedData['from'],
                "currentPage"=>$paginatedData['current_page']
                );

        } catch (ModelNotFoundException $e) {
            $errmsg = $e->getMessage();
            return $this->ErrorResponse($errmsg, 422);
        }  catch (\Exception $e) {
            $errmsg = $e->getMessage();
            return $this->ErrorResponse($errmsg, 422);
        }

        $content = array();
        $content['data'] = $responseArr;
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function getMainWhereQueryParts($completeSelectorQueryBuilderObj, $user, $selectedTab, $subTabArr) {

        $completeSelectorQueryBuilderObj = $completeSelectorQueryBuilderObj->Where(function ($cmquery) use ($user, $selectedTab, $subTabArr) {

            if(in_array($selectedTab, $subTabArr['msg'])){
                $cmquery = $cmquery->orWhere(function ($query) use ($user) {
                    $query = $query->orWhere(SocialMessageUserMap::table.'.'.SocialMessageUserMap::user_id, '=' , $user->id)
                    ->orWhere(SocialMessage::table.'.'.SocialMessage::is_message_to_all, '=', TRUE)
                    ->orWhere(SocialMessage::table.'.'.SocialMessage::creator_user_id, '=', $user->id);
                });
            }

            if(in_array($selectedTab, $subTabArr['task'])){
                $cmquery = $cmquery->orWhere(function ($query) use ($user) {
                    $query =$query->orWhere(TaskParticipants::table.'.'.TaskParticipants::user_id, '=', $user->id)
                    ->orWhere(Task::table.'.'.Task::responsible_person_id, '=', $user->id)
                    ->orWhere(Task::table.'.'.Task::creator_user_id, '=', $user->id)
                    ->orWhere(Task::table.'.'.Task::is_to_allemployees, '=', TRUE);
                });
            }

            if(in_array($selectedTab, $subTabArr['announcement'])){ 
                $cmquery = $cmquery->orWhere(function ($query) use ($user) {
                    $query =$query->orWhere(SocialAnnouncementUserMap::table.'.'.SocialAnnouncementUserMap::user_id, '=', $user->id)
                    ->orWhere(SocialAnnouncement::table.'.'.SocialAnnouncement::is_announcement_to_all, '=', TRUE)
                    ->orWhere(SocialAnnouncement::table.'.'.SocialAnnouncement::creator_user_id, '=', $user->id);
                });
            }

            if(in_array($selectedTab, $subTabArr['poll'])){
                $cmquery = $cmquery->orWhere(function ($query) use ($user) {
                    $query =$query->orWhere(SocialPollInvitedUsers::table.'.'.SocialPollInvitedUsers::user_id, '=', $user->id)
                        ->orWhere(SocialPollGroup::table.'.'.SocialPollGroup::is_poll_to_all, '=', TRUE)
                        ->orWhere(SocialPollGroup::table.'.'.SocialPollGroup::creator_user_id, '=', $user->id);
                });
            }

            if(in_array($selectedTab, $subTabArr['form'])){
                $cmquery = $cmquery->orWhere(function ($query) use ($user) {
                    $query->where(FormMaster::table.'.'.FormMaster::is_published, '=', TRUE)
                    ->where(FormPublishUsers::table.'.'.FormPublishUsers::user_id, '=' , $user->id);
                });
            }

            if(in_array($selectedTab, $subTabArr['event'])){
                $cmquery =$cmquery->orWhere(function ($query) use ($user) {
                $query->orWhere(SocialEventMember::table.'.'.SocialEventMember::user_id, '=', $user->id)
                    ->orWhere(SocialEvent::table.'.'.SocialEvent::is_event_to_all, '=', TRUE)
                    ->orWhere(SocialEvent::table.'.'.SocialEvent::creator_user_id, '=', $user->id);
                });
            }

            if(in_array($selectedTab, $subTabArr['appreciation'])){
                    $cmquery =$cmquery->orWhere(function ($Squery) use ($user) {
                        //normal base query
                        $Squery->Where(function ($query) use ($user) {
                            $query->orWhere(SocialAppreciationUserMap::table.'.'.SocialAppreciationUserMap::user_id, '=', $user->id)
                            //->orWhere(SocialAppreciationRecipientMap::table.'.'.SocialAppreciationRecipientMap::user_id, '=', $user->id)
                            ->orWhere(SocialAppreciation::table.'.'.SocialAppreciation::notify_appreciation_to_all, '=', TRUE)
                            ->orWhere(SocialAppreciation::table.'.'.SocialAppreciation::creator_user_id, '=', $user->id);
                        });

                        //need appreciation display period check.
                        $Squery->Where(function ($query) use ($user) {
                            $query->Where(SocialAppreciation::table.'.'.SocialAppreciation::has_duration, '=', false)
                            ->orWhere(function ($subquery) use ($user) {
                                $subquery->Where(SocialAppreciation::table.'.'.SocialAppreciation::has_duration, '=', true)
                                    ->Where(DB::raw("unix_timestamp(".SocialAppreciation::table.'.'.SocialAppreciation::duration_start.')'), '<=', DB::raw("unix_timestamp(NOW())"))
                                    ->Where(DB::raw("unix_timestamp(".SocialAppreciation::table.'.'.SocialAppreciation::duration_end.')'), '>=', DB::raw("unix_timestamp(NOW())"));
                            });
                        });

                    });
            }

        });
        return $completeSelectorQueryBuilderObj;
    }
    
    
    private function getAllSelectors($activityStreamsQueryBuilderObj,$selectedTab, $subTabArr, $user) {
        //add select for task
            if(in_array($selectedTab, $subTabArr['task'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                Task::table . '.' . Task::slug. ' AS taskSlug',
                Task::table . '.' . Task::title. ' AS taskTitle',
                Task::table . '.' . Task::description. ' AS taskDesc', 
                TaskStatus::table . '.' . TaskStatus::title. ' AS taskStatus',
                DB::raw("unix_timestamp(".Task::table . '.'. Task::end_date.") AS taskEndDate"),
                Task::table . '.' . Task::is_to_allemployees. ' AS taskToAllEmployee',
                'taskCreator.' . User::slug.' AS taskCreatorSlug',
                'taskCreator.' . User::name.' AS taskCreatorName',
                'taskCreator.' . User::email.' AS taskCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", taskCreatorProfile.'. UserProfile::image_path.') as taskCreatorImageUrl'),
                 //count task  subquery     
                DB::raw('( select count('. TaskComments::table. '.' .TaskComments::slug .
                                       ') from '.TaskComments::table.
                        ' where '.TaskComments::table.'.'.TaskComments::task_id.' = '.
                        Task::table.'.id AND '.TaskComments::table.'.'.TaskComments::parent_comment_id.' IS NULL )'.
                        ' AS taskCommentsCount'),
                DB::raw("unix_timestamp(".Task::table . '.'. Task::CREATED_AT.") AS taskCreatedAt"),
                'taskResponsiblePerson.' . User::slug.' AS taskResponsiblePersonSlug',
                'taskResponsiblePerson.' . User::name.' AS taskResponsiblePersonName',
                'taskResponsiblePerson.' . User::email.' AS taskResponsiblePersonEmail',
                DB::raw('GROUP_CONCAT( taskToUser.' .User::slug . ') AS taskUserSlugs'),
                DB::raw('GROUP_CONCAT( taskToUser.' .User::name . ') AS taskUserNames'),
                DB::raw('GROUP_CONCAT( taskToUser.' .User::email . ') AS taskUserEmails'),
                DB::raw('GROUP_CONCAT( coalesce(concat("'. $this->s3BasePath.'", taskToUserProfile.'. UserProfile::image_path.'),"")) as taskToUserProfileImageUrls')
                );
            }
                    
            //add select for event
            if(in_array($selectedTab, $subTabArr['event'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                SocialEvent::table . '.' . SocialEvent::event_slug. ' AS eventSlug',
                SocialEvent::table . '.' . SocialEvent::event_title. ' AS eventTitle',
                SocialEvent::table . '.' . SocialEvent::description. ' AS eventDesc',
                SocialEvent::table . '.' . SocialEvent::is_event_to_all. ' AS eventToAllEmployee',
                DB::raw("unix_timestamp(".SocialEvent::table . '.' . SocialEvent::event_start_date. ') AS eventStart'),
                DB::raw("unix_timestamp(".SocialEvent::table . '.' . SocialEvent::event_end_date. ') AS eventEnd'),
                SocialEvent::table . '.' . SocialEvent::location. ' AS eventLocation',
                SocialEvent::table . '.' . SocialEvent::is_allday. ' AS eventAllDay',
                'eventCreator.' . User::slug.' AS eventCreatorSlug',
                'eventCreator.' . User::name.' AS eventCreatorName',
                'eventCreator.' . User::email.' AS eventCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", eventCreatorProfile.'. UserProfile::image_path.') as eventCreatorImageUrl'),
                DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::CREATED_AT.") AS eventCreatedAt"),
                DB::raw('GROUP_CONCAT( '.SocialEventMember::table.'.' .SocialEventMember::response_status . ') AS eventResponses'),
                DB::raw('GROUP_CONCAT(eventToUser.' .User::slug . ') AS eventUserSlugs'),
                DB::raw('GROUP_CONCAT(eventToUser.' .User::name . ') AS eventUserNames'),
                DB::raw('GROUP_CONCAT(eventToUser.' .User::email . ') AS eventUserEmails'),
                DB::raw('GROUP_CONCAT( coalesce(concat("'. $this->s3BasePath.'", eventToUserProfile.'. UserProfile::image_path.'),"")) as eventToUserImageUrls'),
////                 //count comments  subquery     
                DB::raw('( select count('. SocialEventComment::table. '.' .SocialEventComment::slug .
                                       ') from '.SocialEventComment::table.
                        ' where '.SocialEventComment::table.'.'.SocialEventComment::social_event_id.' = '.
                        SocialEvent::table.'.id AND '.
                        SocialEventComment::table.'.'.SocialEventComment::parent_event_comment_id.' IS NULL )'.
                        ' AS eventCommentsCount'),
                 //count likes  subquery
                DB::raw('( select count('. SocialEventResponse::table. '.' .SocialEventResponse::slug .
                                       ') from '.SocialEventResponse::table.
                        ' where '.SocialEventResponse::table.'.'.SocialEventResponse::event_id.' = '.
                        SocialEvent::table.'.id )'.
                        ' AS eventResponsesCount'),
                        
                 //loggedin user event response  subquery     
                DB::raw('( select '. SocialEventResponse::table. '.' .SocialEventResponse::slug .
                                       ' from '.SocialEventResponse::table.
                        ' where '.SocialEventResponse::table.'.'.SocialEventResponse::event_id.' = '.
                        SocialEvent::table.'.id AND '.
                        SocialEventResponse::table.'.'.SocialEventResponse::user_id.' = '.
                        $user->id.' )'.
                        ' AS yourEventResponseSlug')
                );
            }

            //add select for message
            if(in_array($selectedTab, $subTabArr['msg'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                SocialMessage::table . '.' . SocialMessage::slug. ' AS msgSlug',
                SocialMessage::table . '.' . SocialMessage::title. ' AS msgTitle',
                SocialMessage::table . '.' . SocialMessage::description. ' AS msgDesc',
                SocialMessage::table . '.' . SocialMessage::is_message_to_all. ' AS msgToAllEmployee',
                'msgCreator.' . User::slug.' AS msgCreatorSlug',
                'msgCreator.' . User::name.' AS msgCreatorName',
                'msgCreator.' . User::email.' AS msgCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", msgCreatorProfile.'. UserProfile::image_path.') as msgCreatorImageUrl'),
                DB::raw("unix_timestamp(".SocialMessage::table . '.'.SocialMessage::CREATED_AT.") AS msgCreatedAt"),
                DB::raw('GROUP_CONCAT('.User::table. '.' .User::slug . ') AS userSlugs'),
                DB::raw('GROUP_CONCAT('.User::table. '.' .User::name . ') AS userNames'),
                DB::raw('GROUP_CONCAT('.User::table. '.' .User::email . ') AS userEmails'),
                DB::raw('GROUP_CONCAT( coalesce(concat("'. $this->s3BasePath.'", msgToUserProfile.'. UserProfile::image_path.'),"")) as msgToUserProfileImageUrls'),
                
                 //count comments  subquery     
                DB::raw('( select count('. SocialMessageComment::table. '.' .SocialMessageComment::slug .
                                       ') from '.SocialMessageComment::table.
                        ' where '.SocialMessageComment::table.'.'.SocialMessageComment::social_message_id.' = '.
                        SocialMessage::table.'.id AND '.
                        SocialMessageComment::table.'.'.SocialMessageComment::parent_social_comment_id.' IS NULL )'.
                        ' AS messageCommentsCount'),
                 //count likes  subquery
                DB::raw('( select count('. SocialMessageResponse::table. '.' .SocialMessageResponse::slug .
                                       ') from '.SocialMessageResponse::table.
                        ' where '.SocialMessageResponse::table.'.'.SocialMessageResponse::message_id.' = '.
                        SocialMessage::table.'.id )'.
                        ' AS messageResponsesCount'),
                        
                 //loggedin user message response  subquery     
                DB::raw('( select '. SocialMessageResponse::table. '.' .SocialMessageResponse::slug .
                                       ' from '.SocialMessageResponse::table.
                        ' where '.SocialMessageResponse::table.'.'.SocialMessageResponse::message_id.' = '.
                        SocialMessage::table.'.id AND '.
                        SocialMessageResponse::table.'.'.SocialMessageResponse::user_id.' = '.
                        $user->id.' )'.
                        ' AS yourMessageResponseSlug')

                );
            }

            //add select for announcement
            if(in_array($selectedTab, $subTabArr['announcement'])){ 
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                SocialAnnouncement::table . '.' . SocialAnnouncement::slug. ' AS ancSlug',
                SocialAnnouncement::table . '.' . SocialAnnouncement::title. ' AS ancTitle',
                SocialAnnouncement::table . '.' . SocialAnnouncement::description. ' AS ancDesc',
                SocialAnnouncement::table . '.' . SocialAnnouncement::is_announcement_to_all. ' AS ancToAllEmployee',
                'ancCreator.' . User::slug.' AS ancCreatorSlug',
                'ancCreator.' . User::name.' AS ancCreatorName',
                'ancCreator.' . User::email.' AS ancCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", ancCreatorProfile.'. UserProfile::image_path.') as ancCreatorImageUrl'),
                DB::raw("unix_timestamp(".SocialAnnouncement::table . '.'.SocialAnnouncement::CREATED_AT.") AS ancCreatedAt"),
                DB::raw('GROUP_CONCAT( '.SocialAnnouncementUserMap::table.'.' .SocialAnnouncementUserMap::mark_as_read . ') AS ancMarkAsReads'),
                DB::raw('GROUP_CONCAT( ancToUser.' .User::slug . ') AS ancUserSlugs'),
                DB::raw('GROUP_CONCAT( ancToUser.' .User::name . ') AS ancUserNames'),
                DB::raw('GROUP_CONCAT( ancToUser.' .User::email . ') AS ancUserEmails'),
                DB::raw('GROUP_CONCAT( coalesce( concat("'. $this->s3BasePath.'", ancToUserProfile.'. UserProfile::image_path.'),"")) as ancToUserProfileImageUrls'),
                 //count comments  subquery     
                DB::raw('( select count('. SocialAnnouncementComment::table. '.' .SocialAnnouncementComment::slug .
                                       ') from '.SocialAnnouncementComment::table.
                        ' where '.SocialAnnouncementComment::table.'.'.SocialAnnouncementComment::social_announcement_id.' = '.
                        SocialAnnouncement::table.'.id AND '.
                        SocialAnnouncementComment::table.'.'.SocialAnnouncementComment::parent_announcement_comment_id.' IS NULL )'.
                        ' AS announcementCommentsCount'),
                 //count likes  subquery
                DB::raw('( select count('. SocialAnnouncementResponse::table. '.' .SocialAnnouncementResponse::slug .
                                       ') from '.SocialAnnouncementResponse::table.
                        ' where '.SocialAnnouncementResponse::table.'.'.SocialAnnouncementResponse::annoucement_id.' = '.
                        SocialAnnouncement::table.'.id )'.
                        ' AS announcementResponsesCount'),

                //loggedin user announcement response  subquery     
                DB::raw('( select '. SocialAnnouncementResponse::table. '.' .SocialAnnouncementResponse::slug .
                                       ' from '.SocialAnnouncementResponse::table.
                        ' where '.SocialAnnouncementResponse::table.'.'.SocialAnnouncementResponse::annoucement_id.' = '.
                        SocialAnnouncement::table.'.id AND '.
                        SocialAnnouncementResponse::table.'.'.SocialAnnouncementResponse::user_id.' = '.
                        $user->id.' )'.
                        ' AS yourAnnouncementResponseSlug')
                );
            }

            //add select for poll
            if(in_array($selectedTab, $subTabArr['poll'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                SocialPollGroup::table . '.' . SocialPollGroup::slug. ' AS pollSlug',
                SocialPollGroup::table . '.' . SocialPollGroup::poll_title. ' AS pollTitle',
                SocialPollGroup::table . '.' . SocialPollGroup::poll_description. ' AS pollDesc',
                SocialPollGroup::table . '.' . SocialPollGroup::is_poll_to_all. ' AS pollToAllEmployee',
                'pollStatusLookup.'.SocialLookup::value. ' AS pollStatus',

                'pollCreator.' . User::slug.' AS pollCreatorSlug',
                'pollCreator.' . User::name.' AS pollCreatorName',
                'pollCreator.' . User::email.' AS pollCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", pollCreatorProfile.'. UserProfile::image_path.') as pollCreatorImageUrl'),
                DB::raw("unix_timestamp(".SocialPollGroup::table . '.'.SocialPollGroup::CREATED_AT.") AS pollCreatedAt"),
                DB::raw('GROUP_CONCAT(pollToUser.' .User::slug . ') AS pollUserSlugs'),
                DB::raw('GROUP_CONCAT(pollToUser.' .User::name . ') AS pollUserNames'),
                DB::raw('GROUP_CONCAT(pollToUser.' .User::email . ') AS pollUserEmails'),
                DB::raw('GROUP_CONCAT( coalesce(concat("'. $this->s3BasePath.'", pollToUserProfile.'. UserProfile::image_path.'),"")) as pollToUserProfileImageUrls'),
                 //count comments  subquery     
                DB::raw('( select count('. SocialPollComment::table. '.' .SocialPollComment::slug .
                                       ') from '.SocialPollComment::table.
                        ' where '.SocialPollComment::table.'.'.SocialPollComment::social_pollgroup_id.' = '.
                        SocialPollGroup::table.'.id AND '.
                        SocialPollComment::table.'.'.SocialPollComment::parent_social_comment_id.' IS NULL )'.
                        ' AS pollCommentsCount'),
                 //count likes  subquery
                DB::raw('( select count('. SocialPollResponse::table. '.' .SocialPollResponse::slug .
                                       ') from '.SocialPollResponse::table.
                        ' where '.SocialPollResponse::table.'.'.SocialPollResponse::pollgroup_id.' = '.
                        SocialPollGroup::table.'.id )'.
                        ' AS pollResponsesCount'),

                //loggedin user poll response  subquery     
                DB::raw('( select '. SocialPollResponse::table. '.' .SocialPollResponse::slug .
                                       ' from '.SocialPollResponse::table.
                        ' where '.SocialPollResponse::table.'.'.SocialPollResponse::pollgroup_id.' = '.
                        SocialPollGroup::table.'.id AND '.
                        SocialPollResponse::table.'.'.SocialPollResponse::user_id.' = '.
                        $user->id.' )'.
                        ' AS yourPollResponseSlug')
                        );
            }

            //add select for appreciation
            if(in_array($selectedTab, $subTabArr['appreciation'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                SocialAppreciation::table . '.' . SocialAppreciation::slug. ' AS aprSlug',
                SocialAppreciation::table . '.' . SocialAppreciation::title. ' AS aprTitle',
                SocialAppreciation::table . '.' . SocialAppreciation::description. ' AS aprDesc',

                SocialAppreciation::table . '.' . SocialAppreciation::has_duration. ' AS aprHasDisplayDuration',
                DB::raw("unix_timestamp(".SocialAppreciation::table . '.'.SocialAppreciation::duration_start.") AS aprDisplayStart"),
                DB::raw("unix_timestamp(".SocialAppreciation::table . '.'.SocialAppreciation::duration_end.") AS aprDisplayEnd"),

                SocialAppreciation::table . '.' . SocialAppreciation::notify_appreciation_to_all. ' AS aprToAllEmployee',
                'aprStatusLookup.'.SocialLookup::value. ' AS aprStatus',

                'aprCreator.' . User::slug.' AS aprCreatorSlug',
                'aprCreator.' . User::name.' AS aprCreatorName',
                'aprCreator.' . User::email.' AS aprCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", aprCreatorProfile.'. UserProfile::image_path.') as aprCreatorImageUrl'),
                DB::raw("unix_timestamp(".SocialAppreciation::table . '.'.SocialAppreciation::CREATED_AT.") AS aprCreatedAt"),
                DB::raw('GROUP_CONCAT(aprToUser.' .User::slug . ') AS aprUserSlugs'),
                DB::raw('GROUP_CONCAT(aprToUser.' .User::name . ') AS aprUserNames'),
                DB::raw('GROUP_CONCAT(aprToUser.' .User::email . ') AS aprUserEmails'),
                DB::raw('GROUP_CONCAT(concat("'. $this->s3BasePath.'", aprToUserProfile.'. UserProfile::image_path.')) as aprToUserImageUrls'),
                 //count comments  subquery     
                DB::raw('( select count('. SocialAppreciationComment::table. '.' .SocialAppreciationComment::slug .
                                       ') from '.SocialAppreciationComment::table.
                        ' where '.SocialAppreciationComment::table.'.'.SocialAppreciationComment::social_appreciation_id.' = '.
                        SocialAppreciation::table.'.id AND '.
                        SocialAppreciationComment::table.'.'.SocialAppreciationComment::parent_social_comment_id.' IS NULL )'.
                        ' AS aprCommentsCount'),
                 //count likes  subquery
                DB::raw('( select count('. SocialAppreciationResponse::table. '.' .SocialAppreciationResponse::slug .
                                       ') from '.SocialAppreciationResponse::table.
                        ' where '.SocialAppreciationResponse::table.'.'.SocialAppreciationResponse::appreciation_id.' = '.
                        SocialAppreciation::table.'.id )'.
                        ' AS aprResponsesCount'),

                //loggedin user Appreciation response  subquery     
                DB::raw('( select '. SocialAppreciationResponse::table. '.' .SocialAppreciationResponse::slug .
                                       ' from '.SocialAppreciationResponse::table.
                        ' where '.SocialAppreciationResponse::table.'.'.SocialAppreciationResponse::appreciation_id.' = '.
                        SocialAppreciation::table.'.id AND '.
                        SocialAppreciationResponse::table.'.'.SocialAppreciationResponse::user_id.' = '.
                        $user->id.' )'.
                        ' AS yourAprResponseSlug')

                        );
            }

            //add select for form
            if(in_array($selectedTab, $subTabArr['form'])){
                $activityStreamsQueryBuilderObj = $activityStreamsQueryBuilderObj->addSelect(
                FormMaster::table . '.' . FormMaster::form_slug. ' AS formSlug',
                FormMaster::table . '.' . FormMaster::form_title. ' AS formTitle',
                FormMaster::table . '.' . FormMaster::description. ' AS formDesc',
                FormMaster::table . '.' . FormMaster::is_published. ' AS isPublished',
                FormMaster::table . '.' . FormMaster::allow_multi_submit. ' AS allowMultiSubmit',
                FormAnswerSheet::table . '.' . FormAnswerSheet::slug. ' AS answersheetSlug',
                DB::raw("unix_timestamp(". FormAnswerSheet::table . '.'.FormAnswerSheet::submit_datetime.") AS formSubmittedAt"),
                'FormCreator.' . User::slug.' AS formCreatorSlug',
                'FormCreator.' . User::name.' AS formCreatorName',
                'FormCreator.' . User::email.' AS formCreatorEmail',
                DB::raw('concat("'. $this->s3BasePath.'", formCreatorProfile.'. UserProfile::image_path.') as formCreatorImageUrl'),
                DB::raw("unix_timestamp(". SocialActivityStreamMaster::table . '.'.SocialActivityStreamMaster::CREATED_AT.") AS formPublishedAt"),
                DB::raw("unix_timestamp(".FormMaster::table . '.'.FormMaster::CREATED_AT.") AS formCreatedAt"),
                DB::raw('GROUP_CONCAT('.'FormSendUsers.' .User::slug . ') AS formUserSlugs'),
                DB::raw('GROUP_CONCAT('.'FormSendUsers.' .User::name . ') AS formUserNames'),
                DB::raw('GROUP_CONCAT('.'FormSendUsers.' .User::email . ') AS formUserEmails'),
                DB::raw('GROUP_CONCAT(concat("'. $this->s3BasePath.'", FormSendUsersProfile.'. UserProfile::image_path.')) as FormSendUsersImageUrls')
                );
            }
            return $activityStreamsQueryBuilderObj;
    }

    private function ErrorResponse($errMsg, $code){
        $content = array();
        $content['error'] = array("msg"=>$errMsg);
        $content['code'] = $code;
        $content['status'] = "ERROR";
        return $content; 
    }

    private function fetchAppreciationReciepientsMap($appreciationSlugsArr){
        $aprDetailsQueryResultsObj = DB::table(SocialAppreciation::table)
            ->leftJoin(SocialAppreciationRecipientMap::table, SocialAppreciationRecipientMap::table.'.'.SocialAppreciationRecipientMap::appreciation_id, '=',SocialAppreciation::table . '.id')
            ->leftJoin(User::table, SocialAppreciationRecipientMap::table.'.'.SocialAppreciationRecipientMap::user_id, '=',User::table . '.id')
            ->leftJoin(UserProfile::table, User::table.".id", '=', UserProfile::table. '.' . UserProfile::user_id)
            ->select(
                SocialAppreciation::table.'.'.SocialAppreciation::slug.' as aprSlug',
                SocialAppreciation::table.'.id',
                User::table. '.' .User::slug . ' AS userSlug',
                User::table. '.' .User::name . ' AS userName',
                User::table. '.' .User::email . ' AS userEmail',
                DB::raw('concat("'. $this->s3BasePath.'", '.UserProfile::table.'.'. UserProfile::image_path.') as userImageUrl')
            )->whereIn(SocialAppreciation::table.'.'.SocialAppreciation::slug,$appreciationSlugsArr)
            ->get();

        $aprDetailsMapArr = array();
        $aprDetailsQueryResultsObj->each(function($item) use (&$aprDetailsMapArr){
            //map of pollGroupSlug,pollID => array(polloptions)
            $aprDetailsMapArr[$item->aprSlug][]=(array)$item;
        });

        return $aprDetailsMapArr;
    }

    private function fetchPollDetailsMap($pollGroupSlugsArr, $user){

        $pollDetailsQueryResultsObj = DB::table(SocialPollGroup::table)
            ->join(SocialPoll::table, SocialPollGroup::table . ".id", '=', SocialPoll::table . '.' . SocialPoll::poll_group_id)
            ->leftJoin(SocialPollOptions::table, SocialPollOptions::table.'.'.SocialPollOptions::poll_id, '=',SocialPoll::table . '.id')
            ->select(
                SocialPollGroup::table.'.'.SocialPollGroup::slug.' as pollSlug',
                SocialPoll::table.'.id',
                SocialPoll::table.'.'.SocialPoll::question,
                SocialPoll::table.'.'.SocialPoll::allow_multiple_choice,
                SocialPollOptions::table.'.id AS pollOptId',
                SocialPollOptions::table.'.'.SocialPollOptions::poll_id,
                SocialPollOptions::table.'.'.SocialPollOptions::poll_answeroption,
                SocialPollOptions::table.'.'.SocialPollOptions::creator_user_id
            )->whereIn(SocialPollGroup::table.'.'.SocialPollGroup::slug,$pollGroupSlugsArr)
            ->get();

        $pollDetailsMapArr = array();
        $pollDetailsQueryResultsObj->each(function($item) use (&$pollDetailsMapArr){
            //map of pollGroupSlug,pollID => array(polloptions)
            $pollDetailsMapArr[$item->pollSlug][$item->id][]=(array)$item;
        });

        $pollLoggedInUserAnswerDetailsMapArr = $this->getLoggedInUserVotesArr($pollGroupSlugsArr, $user);
        $pollUserAnswersMapArr = $this->getPollAllAnswersMap($pollGroupSlugsArr);
        return array(
                "pollDetailsMapArr" => $pollDetailsMapArr,
                "pollLoggedInUserAnswerDetailsMapArr" => $pollLoggedInUserAnswerDetailsMapArr,
                "pollUserAnswersMapArr" => $pollUserAnswersMapArr
            );
    }
    
    private function getLoggedInUserVotesArr($pollGroupSlugsArr, $user) {
        $pollLoggedInUserAnswerDetailsQueryResultsObj = DB::table(SocialPollUserAnswers::table)
            ->join(SocialPoll::table, SocialPoll::table . ".id", '=', SocialPollUserAnswers::table . '.' . SocialPollUserAnswers::poll_id)
            ->join(SocialPollGroup::table, SocialPollGroup::table . ".id", '=', SocialPoll::table . '.' . SocialPoll::poll_group_id)
            ->select(
                SocialPollGroup::table.'.'.SocialPollGroup::slug.' as pollSlug',
                SocialPoll::table.'.id',
                SocialPoll::table.'.'.SocialPoll::question,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::poll_id,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::poll_option_id,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::user_id
            )->whereIn(SocialPollGroup::table.'.'.SocialPollGroup::slug,$pollGroupSlugsArr)
             ->where(SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::user_id, '=', $user->id)
            ->get();

        $pollLoggedInUserAnswerDetailsMapArr = array();
        $pollLoggedInUserAnswerDetailsQueryResultsObj->each(function($item) use (&$pollLoggedInUserAnswerDetailsMapArr){
            //map of pollGroupSlug,pollID,poll_option_id => array(polloptions)
            $pollLoggedInUserAnswerDetailsMapArr[$item->pollSlug][$item->id][$item->poll_option_id] = (array)$item;
        });

        return $pollLoggedInUserAnswerDetailsMapArr;
    }

    private function getPollAllAnswersMap($pollGroupSlugsArr) {
        $pollUserAnswersQueryResultsObj = DB::table(SocialPollUserAnswers::table)
            ->join(SocialPoll::table, SocialPoll::table . ".id", '=', SocialPollUserAnswers::table . '.' . SocialPollUserAnswers::poll_id)
            ->join(SocialPollGroup::table, SocialPollGroup::table . ".id", '=', SocialPoll::table . '.' . SocialPoll::poll_group_id)
            ->join(User::table, User::table.'.id','=',SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::user_id)
            ->select(
                SocialPollGroup::table.'.'.SocialPollGroup::slug.' as pollSlug',
                SocialPoll::table.'.id',
                SocialPoll::table.'.'.SocialPoll::question,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::poll_id,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::poll_option_id,
                SocialPollUserAnswers::table.'.'.SocialPollUserAnswers::user_id,
                User::slug,
                User::name,
                User::email
            )->whereIn(SocialPollGroup::table.'.'.SocialPollGroup::slug,$pollGroupSlugsArr)
            ->get();

        $pollUserAnswersMapArr = array();
        $pollUserAnswersQueryResultsObj->each(function($item) use (&$pollUserAnswersMapArr){
            //map of pollGroupSlug,pollID,poll_option_id => array(polloptions)
            $pollUserAnswersMapArr[$item->pollSlug][$item->id][$item->poll_option_id][] = (array)$item;
        });
        
        return $pollUserAnswersMapArr;
    }

    private function fetchSingleStreamItemObj($param, $pollDetailsCMPMap, $aprRecepientsMap) {
        $dataObj=array();
        $type = $param->type;
        switch ($type){
            case "task":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getTaskObjArr($param);
                break;
            case "message":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getMessageObjArr($param);
                break;
            case "announcement":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getAnnounceObjArr($param);
                break;
            case "event":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getEventObjArr($param);
                break;
            case "poll":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getPollGroupObjArr($param, $pollDetailsCMPMap);
                break;
            case "appreciation":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getAppreciationObjArr($param, $aprRecepientsMap);
                break;
            case "form":
                $dataObj['type'] = $type;
                $dataObj['activityStreamMasterId'] = $param->activityStreamMasterId;
                $dataObj['note'] = $param->note;
                $dataObj[$type] = $this->getFormObjArr($param);
                break;
            default :
                throw new \Exception("unhandled activity stream type found!");
        }
        return $dataObj;
    }

    private function getPollGroupObjArr($resultsObj, $pollCompleteDetailsMap) {
        $pollGroupObj=array();
        $pollGroupObj['pollSlug'] = $resultsObj->pollSlug;
        $pollGroupObj['pollTitle'] = $resultsObj->pollTitle;
        $pollGroupObj['pollDesc'] = $resultsObj->pollDesc;
        $pollGroupObj['status'] = $resultsObj->pollStatus;
        $pollGroupObj['toAllEmployee'] = (boolean)$resultsObj->pollToAllEmployee;
        $pollGroupObj['pollCreatorSlug'] = $resultsObj->pollCreatorSlug;
        $pollGroupObj['pollCreatorName'] = $resultsObj->pollCreatorName;
        $pollGroupObj['pollCreatorEmail'] = $resultsObj->pollCreatorEmail;
        $pollGroupObj['pollCreatorImageUrl'] = $resultsObj->pollCreatorImageUrl;
        $pollGroupObj['createdAt'] = $resultsObj->pollCreatedAt;

        $pollUserAnswersMapArr = $pollCompleteDetailsMap["pollUserAnswersMapArr"];
        $pollDetailsMap = $pollCompleteDetailsMap["pollDetailsMapArr"];
        
        
        $pollLoggedInUserAnswerDetailsMapArr = $pollCompleteDetailsMap["pollLoggedInUserAnswerDetailsMapArr"];
        $singlePollLoggedInUserAnswerDetailsMapArr = !empty($pollLoggedInUserAnswerDetailsMapArr[$resultsObj->pollSlug]) ? $pollLoggedInUserAnswerDetailsMapArr[$resultsObj->pollSlug]:[];
        
        $singlePollAllAnsweredUsersMapArr = !empty($pollUserAnswersMapArr[$resultsObj->pollSlug]) ? $pollUserAnswersMapArr[$resultsObj->pollSlug]:[];

        $pollDetailsArr = !empty($pollDetailsMap[$resultsObj->pollSlug]) ? $pollDetailsMap[$resultsObj->pollSlug]:[];

        $pollQuestionsArr = array();
        foreach ($pollDetailsArr as $key => $optionsArr) {

            $tempOPArr = array();
            $tempOPArr['pollQuestionId'] = $key;
            $tempOPArr['pollQuestion'] = $optionsArr[0]['question'];
            $tempOPArr['allowMultipleChoice'] = (boolean)$optionsArr[0]['allow_multiple_choice'];
            $tempOPArr['answerOptions'] = array();
            foreach($optionsArr as $option){
                
                //check for if logged in user has voted answer
                $selected = !empty($singlePollLoggedInUserAnswerDetailsMapArr[$tempOPArr['pollQuestionId']][$option['pollOptId']])?
                        true:false;
                
                $singlePollAllUserAnswers = !empty($singlePollAllAnsweredUsersMapArr[$tempOPArr['pollQuestionId']][$option['pollOptId']])?$singlePollAllAnsweredUsersMapArr[$tempOPArr['pollQuestionId']][$option['pollOptId']]:[];
                
                $pollResults = array(
                "totalSelected" => count($singlePollAllUserAnswers)
                );
                
                //pollOptId , poll_answeroption
                array_push($tempOPArr['answerOptions'], 
                    array(
                    "pollOptId" => $option['pollOptId'],
                    "pollOption" => $option['poll_answeroption'],
                    "selected" => $selected,
                    "pollResult" => $pollResults
                ));
            }

            array_push($pollQuestionsArr, $tempOPArr);
        }

        $pollGroupObj['pollQuestions'] = $pollQuestionsArr;

        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->pollUserSlugs) ? explode(",", $resultsObj->pollUserSlugs):[];
        $userNamesArr = !empty($resultsObj->pollUserNames) ? explode(",", $resultsObj->pollUserNames):[];
        $userEmailsArr = !empty($resultsObj->pollUserEmails) ? explode(",", $resultsObj->pollUserEmails):[];
        $pollToUserProfileImageUrlsArr = !empty($resultsObj->pollToUserProfileImageUrls)?  explode(",", $resultsObj->pollToUserProfileImageUrls):[];
       
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $pollToUserProfileImageUrlsArr){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($pollToUserProfileImageUrlsArr[$key]) ? $pollToUserProfileImageUrlsArr[$key] : null,
                ));
        });
        $pollGroupObj['toUsers'] = $toUsersArr;
        $pollGroupObj['pollCommentsCount'] = $resultsObj->pollCommentsCount;
        $pollGroupObj['pollResponsesCount'] = $resultsObj->pollResponsesCount;
        $pollGroupObj['yourPollResponse'] = !empty($resultsObj->yourPollResponseSlug)? "like" : null;
        $pollGroupObj['yourPollResponseSlug'] = !empty($resultsObj->yourPollResponseSlug)? $resultsObj->yourPollResponseSlug : null;
        return $pollGroupObj;
    }
    
    private function getAppreciationObjArr($resultsObj, $aprDetailsMap) {
        $aprObj=array();
        $aprObj['aprSlug'] = $resultsObj->aprSlug;
        $aprObj['aprTitle'] = $resultsObj->aprTitle;
        $aprObj['aprDesc'] = $resultsObj->aprDesc;
        $aprObj['status'] = $resultsObj->aprStatus;
        $aprObj['toAllEmployee'] = (boolean)$resultsObj->aprToAllEmployee;
        $aprObj['aprHasDisplayDuration'] = (boolean)$resultsObj->aprHasDisplayDuration;
        $aprObj['aprDisplayStart'] = $resultsObj->aprDisplayStart;
        $aprObj['aprDisplayEnd'] = $resultsObj->aprDisplayEnd;
        $aprObj['aprCreatorSlug'] = $resultsObj->aprCreatorSlug;
        $aprObj['aprCreatorName'] = $resultsObj->aprCreatorName;
        $aprObj['aprCreatorEmail'] = $resultsObj->aprCreatorEmail;
        $aprObj['aprCreatorImageUrl'] = $resultsObj->aprCreatorImageUrl;
        $aprObj['createdAt'] = $resultsObj->aprCreatedAt;

        $aprDetailsArr = !empty($aprDetailsMap[$resultsObj->aprSlug]) ? $aprDetailsMap[$resultsObj->aprSlug]:[];

        $recepientsArr = array();
        foreach ($aprDetailsArr as $recepientArr) {

            $tempOPArr = array();
            $tempOPArr['userSlug'] = $recepientArr['userSlug'];
            $tempOPArr['userName'] = $recepientArr['userName'];
            $tempOPArr['userEmail'] = $recepientArr['userEmail'];
            $tempOPArr['userImageUrl'] = !empty($recepientArr["userImageUrl"])?$recepientArr["userImageUrl"]:null;
            array_push($recepientsArr, $tempOPArr);
        }

        $aprObj['recipients'] = $recepientsArr;

        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->aprUserSlugs) ? explode(",", $resultsObj->aprUserSlugs):[];
        $userNamesArr = !empty($resultsObj->aprUserNames) ? explode(",", $resultsObj->aprUserNames):[];
        $userEmailsArr = !empty($resultsObj->aprUserEmails) ? explode(",", $resultsObj->aprUserEmails):[];
        $aprToUserImageUrls = !empty($resultsObj->aprToUserImageUrls) ? explode(",", $resultsObj->aprToUserImageUrls):[];
       
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $aprToUserImageUrls){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($aprToUserImageUrls[$key])?$aprToUserImageUrls[$key]:null
                ));
        });
        $aprObj['toUsers'] = $toUsersArr;
        $aprObj['aprCommentsCount'] = $resultsObj->aprCommentsCount;
        $aprObj['aprResponsesCount'] = $resultsObj->aprResponsesCount;
        $aprObj['yourAprResponse'] = !empty($resultsObj->yourAprResponseSlug)? "like" : null;
        $aprObj['yourAprResponseSlug'] = !empty($resultsObj->yourAprResponseSlug)? $resultsObj->yourAprResponseSlug : null;
        return $aprObj;
    }

    private function getTaskObjArr($resultsObj){
        $taskObj=array();
        $taskObj['taskSlug'] = $resultsObj->taskSlug;
        $taskObj['taskTitle'] = $resultsObj->taskTitle;
        $taskObj['taskDesc'] = $resultsObj->taskDesc;
        $taskObj['taskStatus'] = $resultsObj->taskStatus;
        $taskObj['taskEndDate'] = $resultsObj->taskEndDate;
        $taskObj['taskCreatorSlug'] = $resultsObj->taskCreatorSlug;
        $taskObj['taskCreatorName'] = $resultsObj->taskCreatorName;
        $taskObj['taskCreatorEmail'] = $resultsObj->taskCreatorEmail;
        $taskObj['taskCreatorImageUrl'] = $resultsObj->taskCreatorImageUrl;
        $taskObj['taskToAllEmployee'] = (boolean)$resultsObj->taskToAllEmployee;

        $taskObj['taskResponsiblePersonSlug'] = $resultsObj->taskResponsiblePersonSlug;
        $taskObj['taskResponsiblePersonName'] = $resultsObj->taskResponsiblePersonName;
        $taskObj['taskResponsiblePersonEmail'] = $resultsObj->taskResponsiblePersonEmail;
        $taskObj['createdAt'] = $resultsObj->taskCreatedAt;
        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->taskUserSlugs) ? explode(",", $resultsObj->taskUserSlugs):[];
        $userNamesArr = !empty($resultsObj->taskUserNames) ? explode(",", $resultsObj->taskUserNames):[];
        $userEmailsArr = !empty($resultsObj->taskUserEmails) ? explode(",", $resultsObj->taskUserEmails):[];
        $taskToUserProfileImageUrlsArr = !empty($resultsObj->taskToUserProfileImageUrls) ? explode(",", $resultsObj->taskToUserProfileImageUrls):[];
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $taskToUserProfileImageUrlsArr){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($taskToUserProfileImageUrlsArr[$key]) ? $taskToUserProfileImageUrlsArr[$key]:null,
                ));
        });
        $taskObj['toUsers'] = $toUsersArr;
        $taskObj['taskCommentsCount'] = $resultsObj->taskCommentsCount;
        return $taskObj;
    }

    private function getEventObjArr($resultsObj) {
        $eventObj=array();
        $eventObj['eventSlug'] = $resultsObj->eventSlug;
        $eventObj['eventTitle'] = $resultsObj->eventTitle;
        $eventObj['eventDesc'] = $resultsObj->eventDesc;
        $eventObj['eventLocation'] = $resultsObj->eventLocation;
        $eventObj['eventStart'] = $resultsObj->eventStart;
        $eventObj['eventEnd'] = $resultsObj->eventEnd;
        $eventObj['eventAllDay'] = (boolean)$resultsObj->eventAllDay;
        $eventObj['toAllEmployee'] = (boolean)$resultsObj->eventToAllEmployee;
        $eventObj['eventCreatorSlug'] = $resultsObj->eventCreatorSlug;
        $eventObj['eventCreatorName'] = $resultsObj->eventCreatorName;
        $eventObj['eventCreatorEmail'] = $resultsObj->eventCreatorEmail;
        $eventObj['eventCreatorImageUrl'] = $resultsObj->eventCreatorImageUrl;
        $eventObj['createdAt'] = $resultsObj->eventCreatedAt;
        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->eventUserSlugs) ? explode(",", $resultsObj->eventUserSlugs):[];
        $userNamesArr = !empty($resultsObj->eventUserNames) ? explode(",", $resultsObj->eventUserNames):[];
        $userEmailsArr = !empty($resultsObj->eventUserEmails) ? explode(",", $resultsObj->eventUserEmails):[];
        $eventToUserImageUrls = !empty($resultsObj->eventToUserImageUrls)? explode(",", $resultsObj->eventToUserImageUrls):[];
        $eventResponses = !empty($resultsObj->eventResponses) ? explode(",", $resultsObj->eventResponses):[];
        
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $eventResponses, $eventToUserImageUrls){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($eventToUserImageUrls[$key])?$eventToUserImageUrls[$key]:null,
                "eventResponse" => !empty($eventResponses[$key])?$eventResponses[$key]:""
                ));
        });
        $eventObj['toUsers'] = $toUsersArr;
        $eventObj['eventCommentsCount'] = $resultsObj->eventCommentsCount;
        $eventObj['eventResponsesCount'] = $resultsObj->eventResponsesCount;
        $eventObj['yourEventResponse'] = !empty($resultsObj->yourEventResponseSlug)? "like" : null;
        $eventObj['yourEventResponseSlug'] = !empty($resultsObj->yourEventResponseSlug)? $resultsObj->yourEventResponseSlug : null;
        return $eventObj;
    }
    
    private function getAnnounceObjArr($resultsObj) {
        $ancObj=array();
        $ancObj['ancSlug'] = $resultsObj->ancSlug;
        $ancObj['ancTitle'] = $resultsObj->ancTitle;
        $ancObj['ancDesc'] = $resultsObj->ancDesc;
        $ancObj['toAllEmployee'] = (boolean)$resultsObj->ancToAllEmployee;
        $ancObj['ancCreatorSlug'] = $resultsObj->ancCreatorSlug;
        $ancObj['ancCreatorName'] = $resultsObj->ancCreatorName;
        $ancObj['ancCreatorEmail'] = $resultsObj->ancCreatorEmail;
        $ancObj['ancCreatorImageUrl'] = $resultsObj->ancCreatorImageUrl;
        $ancObj['createdAt'] = $resultsObj->ancCreatedAt;
        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->ancUserSlugs) ? explode(",", $resultsObj->ancUserSlugs):[];
        $userNamesArr = !empty($resultsObj->ancUserNames) ? explode(",", $resultsObj->ancUserNames):[];
        $userEmailsArr = !empty($resultsObj->ancUserEmails) ? explode(",", $resultsObj->ancUserEmails):[];
        $ancMarkAsReads = !empty($resultsObj->ancMarkAsReads) ? explode(",", $resultsObj->ancMarkAsReads):[];
        $ancToUserProfileImageUrls = !empty($resultsObj->ancToUserProfileImageUrls) ? explode(",", $resultsObj->ancToUserProfileImageUrls):[];
        
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $ancMarkAsReads, $ancToUserProfileImageUrls){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($ancToUserProfileImageUrls[$key])?$ancToUserProfileImageUrls[$key]:null,
                "hasRead" => !empty($ancMarkAsReads[$key])?(boolean)$ancMarkAsReads[$key]:false
                ));
        });
        $ancObj['toUsers'] = $toUsersArr;
        $ancObj['announcementCommentsCount'] = $resultsObj->announcementCommentsCount;
        $ancObj['announcementResponsesCount'] = $resultsObj->announcementResponsesCount;
        $ancObj['yourAnnouncementResponse'] = !empty($resultsObj->yourAnnouncementResponseSlug)? "like" : null;
        $ancObj['yourAnnouncementResponseSlug'] = !empty($resultsObj->yourAnnouncementResponseSlug)? $resultsObj->yourAnnouncementResponseSlug : null;
        return $ancObj;
    }

    private function getMessageObjArr($resultsObj) {
        $messageObj=array();
        $messageObj['msgSlug'] = $resultsObj->msgSlug;
        $messageObj['msgTitle'] = $resultsObj->msgTitle;
        $messageObj['msgDesc'] = $resultsObj->msgDesc;
        $messageObj['toAllEmployee'] = (boolean)$resultsObj->msgToAllEmployee;
        $messageObj['msgCreatorSlug'] = $resultsObj->msgCreatorSlug;
        $messageObj['msgCreatorName'] = $resultsObj->msgCreatorName;
        $messageObj['msgCreatorEmail'] = $resultsObj->msgCreatorEmail;
        $messageObj['msgCreatorImageUrl'] = $resultsObj->msgCreatorImageUrl;
        $messageObj['createdAt'] = $resultsObj->msgCreatedAt;
        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->userSlugs) ? explode(",", $resultsObj->userSlugs):[];
        $userNamesArr = !empty($resultsObj->userNames) ? explode(",", $resultsObj->userNames):[];
        $userEmailsArr = !empty($resultsObj->userEmails) ? explode(",", $resultsObj->userEmails):[];
        $msgToUserProfileImageUrls = !empty($resultsObj->msgToUserProfileImageUrls) ? explode(",", $resultsObj->msgToUserProfileImageUrls):[];
        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $msgToUserProfileImageUrls){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($msgToUserProfileImageUrls[$key])?$msgToUserProfileImageUrls[$key]:null,
                ));
        });
        $messageObj['toUsers'] = $toUsersArr;
        $messageObj['messageCommentsCount'] = $resultsObj->messageCommentsCount;
        $messageObj['messageResponsesCount'] = $resultsObj->messageResponsesCount;
        $messageObj['yourMessageResponse'] = !empty($resultsObj->yourMessageResponseSlug)? "like" : null;
        $messageObj['yourMessageResponseSlug'] = !empty($resultsObj->yourMessageResponseSlug)? $resultsObj->yourMessageResponseSlug : null;
        return $messageObj;
    }

    private function getFormObjArr($resultsObj) {
        $formObj=array();
        $formObj['formSlug'] = $resultsObj->formSlug;
        $formObj['formTitle'] = $resultsObj->formTitle;
        $formObj['formDesc'] = $resultsObj->formDesc;
        $formObj['isPublished'] = (boolean)$resultsObj->isPublished;
        $formObj['allowMultiSubmit'] = (boolean)$resultsObj->allowMultiSubmit;
        $formObj['answersheetSlug'] = $resultsObj->answersheetSlug;
        $formObj['formSubmittedAt'] = $resultsObj->formSubmittedAt;
        $formObj['formCreatorSlug'] = $resultsObj->formCreatorSlug;
        $formObj['formCreatorName'] = $resultsObj->formCreatorName;
        $formObj['formCreatorEmail'] = $resultsObj->formCreatorEmail;
        $formObj['formCreatorImageUrl'] = $resultsObj->formCreatorImageUrl;
        $formObj['formPublishedAt'] = $resultsObj->formPublishedAt;
        $formObj['formCreatedAt'] = $resultsObj->formCreatedAt;

        $buttonSetting = array();

        if($formObj['allowMultiSubmit']){
            $buttonSetting = array("showGoToForm"=>TRUE);
        } else {
            if(empty($formObj['formSubmittedAt'])){
                $buttonSetting = array("showGoToForm"=>TRUE);
            } else {
                if($formObj['formPublishedAt'] > $formObj['formSubmittedAt']){
                    $buttonSetting = array("showGoToForm"=>TRUE);
                } else {
                    $buttonSetting = array("showGoToForm"=>FALSE);
                }
            }
        }

        $formObj['buttonSetting'] = $buttonSetting;

        $toUsersArr = array();
        $userSlugsArr = !empty($resultsObj->formUserSlugs) ? explode(",", $resultsObj->formUserSlugs):[];
        $userNamesArr = !empty($resultsObj->formUserNames) ? explode(",", $resultsObj->formUserNames):[];
        $userEmailsArr = !empty($resultsObj->formUserEmails) ? explode(",", $resultsObj->formUserEmails):[];
        $FormSendUsersImageUrls = !empty($resultsObj->formUserEmails) ? explode(",", $resultsObj->formUserEmails):[];

        collect($userSlugsArr)->each(function($it, $key) use(&$toUsersArr, $userNamesArr, $userEmailsArr, $FormSendUsersImageUrls){
            array_push(
                $toUsersArr, 
                array(
                "userSlug" => $it,
                "userName" => $userNamesArr[$key],
                "userEmail" => $userEmailsArr[$key],
                "userImageUrl" => !empty($FormSendUsersImageUrls[$key])?!empty($FormSendUsersImageUrls[$key]):null,
                ));
        });
        $formObj['toUsers'] = $toUsersArr;
        return $formObj;
    }
}