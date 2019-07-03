<?php

namespace Modules\SocialModule\Repositories\Event;

use Modules\SocialModule\Repositories\EventRepositoryInterface;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\SocialModule\Entities\SocialActivityStreamEventMap;
use Carbon\Carbon;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{

    private $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    /*
     * create, update and delete event
     */
    public function createEvent($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
            if($request->action == 'create'){

                $event   = new SocialEvent();
                $event->{SocialEvent::event_slug} = Utilities::getUniqueId();
                $this->setEventFactory($event, $request, $user, "Event created");

            } else if($request->action == 'update'){
                $event = SocialEvent::where(SocialEvent::event_slug, '=',$request->eventSlug)
                        ->first();
                if(empty($event)){
                    throw new \Exception("Invalid Event");
                }
                $this->setEventFactory($event, $request, $user, "Event Updated");

            } else if($request->action == 'delete'){

                $event = DB::table(SocialEvent::table)
                        ->where(SocialEvent::event_slug, '=',$request->eventSlug)
                        ->first();
                if(empty($event)){
                    throw new \Exception("Invalid Event");
                }
                
                $socialActivityStreamEventMaps = DB::table(SocialActivityStreamEventMap::table)
                ->where(SocialActivityStreamEventMap::event_id, '=', $event->id)->get();
                
                $socialActivityStreamEventMaps->each(function($singleSocialActivityStreamEventMap){
                    DB::table(SocialActivityStreamMaster::table)
                    ->where("id", '=', $singleSocialActivityStreamEventMap->{SocialActivityStreamEventMap::activity_stream_master_id})
                    ->delete();
                });

                DB::table(SocialEvent::table)
                ->where(SocialEvent::event_slug, '=', $request->eventSlug)
                ->delete();
            }
            DB::commit();
        } catch (ModelNotFoundException $e) {
            $errmsg = $e->getMessage();
            DB::rollBack();
            $content = array();
            $content['error']   = array("msg"=>$errmsg);
            $content['code']    =  422;
            $content['status']  = "ERROR";
            return $content;
        }  catch (\Exception $e) {
            $errmsg = $e->getMessage();
            DB::rollBack();
            $content = array();
            $content['error']   = array("msg"=>$errmsg);
            $content['code']    =  422;
            $content['status']  = "ERROR";
            return $content;
        }

        $content = array();
        $content['data'] = array();
        if($request->action == "create"){
            $content['data']['msg'] = "Event created successfully";
        } else if($request->action == "update"){
            $content['data']['msg'] = "Event updated successfully";
        } else if($request->action == "delete"){
            $content['data']['msg'] = "Event deleted successfully";
        }
        $content['data']["eventSlug"] = $event->{SocialEvent::event_slug};
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    
    private function setEventFactory(&$event, $request, $user, $activityNote){
        $event->{SocialEvent::event_title} = $request->eventTitle;
        $event->{SocialEvent::description}  = $request->eventDesc;

        $startDateTime = Carbon::createFromTimestamp($request->eventStart);
        $endDateTime      = Carbon::createFromTimestamp($request->eventEnd);

        if(empty($request->eventAllDay)){
            if( !(($startDateTime < $endDateTime) && ($endDateTime->diffInMinutes($startDateTime) >= 15)) ) {
                throw new \Exception("Invalid event start/end date");
            }
        } else {
            if( !($startDateTime <= $endDateTime)  ) {
                throw new \Exception("Invalid event start/end date");
            }
        }

        $event->{SocialEvent::event_start_date}  = !empty($request->eventStart) ? date('Y-m-d H:i:s', $request->eventStart):null;
        $event->{SocialEvent::event_end_date}  = !empty($request->eventEnd) ? date('Y-m-d H:i:s', $request->eventEnd):null;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $event->{SocialEvent::org_id}  = $organisationObj->id;
        $event->{SocialEvent::creator_user_id}  = $user->id;
        $event->{SocialEvent::is_event_to_all}  = $request->toAllEmployee;
        $event->{SocialEvent::is_allday}  = !empty($request->eventAllDay) ? $request->eventAllDay : null;

        if(empty($request->eventLocation)){
            throw new \Exception("Invalid eventLocation");
        }
        $event->{SocialEvent::location}  = !empty($request->eventLocation) ? $request->eventLocation : null;


        $event->{SocialEvent::reminder_count}  = (!empty($request->reminder) && !empty($request->reminder['count'])) ? 
                $request->reminder['count'] : null;

        $eventReminderTypeLookUpId = $this->commonRepository->getLookupId("event", "reminder", 
                  (!empty($request->reminder) && !empty($request->reminder['type'])) ? $request->reminder['type'] : null
                );
        $event->{SocialEvent::reminder_type_id}  = $eventReminderTypeLookUpId;

        $event->{SocialEvent::reminder_datetime}  = null; //TODO

        $eventImportanceLookUpId = $this->commonRepository->getLookupId("event", "importance", $request->eventImportance);
        $event->{SocialEvent::importance_lookup_id}  = $eventImportanceLookUpId;

        $eventAvailabilityLookUpId = $this->commonRepository->getLookupId("event", "availability",$request->eventAvailability);
        $event->{SocialEvent::availabilty_lookup_id}  = $eventAvailabilityLookUpId;

        $eventRepeatLookUpId = $this->commonRepository->getLookupId("event", "repeat",$request->eventRepeat);
        $event->{SocialEvent::repeat_lookup_id}  = $eventRepeatLookUpId;

        $event->{SocialEvent::user_calender_id}  = null;
        $event->save();


        //activityStream
        $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $event, $activityNote);
        $toObj = collect($request->toUsers);

        $this->setEventUserMapObjs($toObj, $event, $socialActivityStreamMaster);
    }

    public function setEventUserMapObjs($toObj, $event, $socialActivityStreamMaster) {

        $toUserIdArr = array();
        $toObj->each(function ($item) use ($event, &$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleSocialEventUserMap($item, $event, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialEventMember::table)
        ->where(SocialEventMember::social_event_id, '=', $event->id)
        ->whereNotIn(SocialEventMember::user_id, $toUserIdArr)
        ->delete();
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }

    public function setSingleSocialEventUserMap($item, $event, $socialActivityStreamMaster) {
        
        $toUser = $this->commonRepository->getUser($item);
        
        $socialEventUserMapObj = SocialEventMember::
                where(SocialEventMember::social_event_id, '=',$event->id)
                ->where(SocialEventMember::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialEventUserMapObj)){
            $socialEventUserMapObj = new SocialEventMember;
            $socialEventUserMapObj->{SocialEventMember::user_id} = $toUser->id ;
            $socialEventUserMapObj->{SocialEventMember::social_event_id} = $event->id;
            $socialEventUserMapObj->{SocialEventMember::response_status} = "";
            $socialEventUserMapObj->save();
        }
        
        //activity stream
        $socialActivityStreamUserObj = SocialActivityStreamUser::where(SocialActivityStreamUser::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamUser::to_user_id, '=',$toUser->id)
                ->first();
        if(empty($socialActivityStreamUserObj)){
            $socialActivityStreamUserObj = new SocialActivityStreamUser;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::to_user_id} = $toUser->id ;
            $socialActivityStreamUserObj->{SocialActivityStreamUser::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamUserObj->save();
        }
        return $socialEventUserMapObj->{SocialEventMember::user_id};
    }

    public function setSocialActivityStream($user,$organisationObj,$event, $note) {
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"event")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $user->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterEventMap = DB::table(SocialActivityStreamEventMap::table)
                ->where(SocialActivityStreamEventMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamEventMap::event_id, '=',$event->id)
                ->first();

        if(empty($socialActivityStreamMasterEventMap)){
            
            $this->updateOldEventActivityStream($event);
            $socialActivityStreamMasterEventMap = new SocialActivityStreamEventMap;
            $socialActivityStreamMasterEventMap->{SocialActivityStreamEventMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterEventMap->{SocialActivityStreamEventMap::event_id} = $event->id;
            $socialActivityStreamMasterEventMap->save();
        }
        return $socialActivityStreamMaster;
    }

    private function updateOldEventActivityStream($event) {

        $socialActivityStreamMasterMappingCheckMap = DB::table(SocialActivityStreamEventMap::table)
        ->where(SocialActivityStreamEventMap::event_id, '=',$event->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamEventMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }

    public function setEventStatus($request) {
       
        $user = Auth::user();
        DB::beginTransaction();
        try {
        $event = SocialEvent::where(SocialEvent::event_slug, '=',$request->eventSlug)
                        ->first();
        if(empty($event)){
            throw new \Exception("Invalid Event");
        }
        
        if(!in_array($request->eventResponse, array("going","decline"))){
            throw new \Exception("Invalid eventResponse, valid are 'going' or 'decline' ");
        }
        $socialEventUserMapObj = SocialEventMember::
                where(SocialEventMember::social_event_id, '=',$event->id)
                ->where(SocialEventMember::user_id, '=',$user->id)
                ->first();

        //if is_event_to_all (true) then add to SocialEventMember
        if( empty($socialEventUserMapObj) && $event->{SocialEvent::is_event_to_all} ){
            $socialEventUserMapObj = new SocialEventMember();
            $socialEventUserMapObj->{SocialEventMember::social_event_id} = $event->id;
            $socialEventUserMapObj->{SocialEventMember::user_id} = $user->id;
        }

        if(empty($socialEventUserMapObj)){
            throw new \Exception("Invalid setEventStatus update");
        }

        $socialEventUserMapObj->{SocialEventMember::response_status} = $request->eventResponse;
        $socialEventUserMapObj->save();
        DB::commit();
        } catch (\Exception $e) {
            $errmsg = $e->getMessage();
            DB::rollBack();
            $content = array();
            $content['error']   = array("msg"=>$errmsg);
            $content['code']    =  422;
            $content['status']  = "ERROR";
            return $content;
        }

        $content = array();
        $content['data'] = array("msg"=>"Event response updated successfully");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    public function getEvent($request) {
        try {
            $event = DB::table(SocialEvent::table)
                ->leftJoin(SocialEventMember::table, SocialEvent::table . ".id", '=', SocialEventMember::table . '.' . SocialEventMember::social_event_id)
                ->leftJoin(User::table.' as eventCreator', "eventCreator.id", '=', SocialEvent::table . '.' . SocialEvent::creator_user_id)
                ->leftJoin(User::table.' as eventToUser', "eventToUser.id", '=', SocialEventMember::table . '.' . SocialEventMember::user_id)
                ->leftJoin(SocialLookup::table.' as ImportanceLookUp', "ImportanceLookUp.id", '=', SocialEvent::table . '.' . SocialEvent::importance_lookup_id)
                ->leftJoin(SocialLookup::table.' as AvailabilityLookUp', "AvailabilityLookUp.id", '=', SocialEvent::table . '.' . SocialEvent::availabilty_lookup_id)
                ->leftJoin(SocialLookup::table.' as RepeatLookUp', "RepeatLookUp.id", '=', SocialEvent::table . '.' . SocialEvent::repeat_lookup_id)
                ->leftJoin(SocialLookup::table.' as ReminderTypeLookUp', "ReminderTypeLookUp.id", '=', SocialEvent::table . '.' . SocialEvent::reminder_type_id)
                ->where(SocialEvent::event_slug, '=',$request->eventSlug)
                ->select(
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
                    'ImportanceLookUp.' . SocialLookup::value.' AS eventImportance',
                    'AvailabilityLookUp.' . SocialLookup::value.' AS eventAvailability',
                    'RepeatLookUp.' . SocialLookup::value.' AS eventRepeat',
                    'ReminderTypeLookUp.' . SocialLookup::value.' AS eventReminderType',
                    SocialEvent::table . '.' . SocialEvent::reminder_count. ' AS eventReminderCount',
                    DB::raw("unix_timestamp(".SocialEvent::table . '.'.SocialEvent::CREATED_AT.") AS eventCreatedAt"),

                    SocialEventMember::table.'.' .SocialEventMember::response_status . ' AS eventResponse',
                    'eventToUser.' .User::slug . ' AS eventUserSlug',
                    'eventToUser.' .User::name . ' AS eventUserName',
                    'eventToUser.' .User::email . ' AS eventUserEmail'
                )
                ->get();
            if(empty($event)){
                throw new \Exception("Invalid EventSlug");
            }
            
            $responseArr = array();
            $eventMembers = array();
            $event->each(function($item) use (&$responseArr, &$eventMembers) {
                $responseArr['eventSlug'] = $item->eventSlug;
                $responseArr['eventTitle'] = $item->eventTitle;
                $responseArr['eventDesc'] = $item->eventDesc;
                $responseArr['eventToAllEmployee'] = (boolean)$item->eventToAllEmployee;
                $responseArr['eventStart'] = $item->eventStart;
                $responseArr['eventEnd'] = $item->eventEnd;
                $responseArr['eventLocation'] = $item->eventLocation;
                $responseArr['eventAllDay'] = (boolean)$item->eventAllDay;
                $responseArr['eventCreatorSlug'] = $item->eventCreatorSlug;
                $responseArr['eventCreatorName'] = $item->eventCreatorName;
                $responseArr['eventCreatorEmail'] = $item->eventCreatorEmail;
                $responseArr['eventImportance'] = $item->eventImportance;
                $responseArr['eventAvailability'] = $item->eventAvailability;
                $responseArr['eventRepeat'] = $item->eventRepeat;
                $responseArr['eventCreatedAt'] = $item->eventCreatedAt;
                $responseArr['reminder'] = array(
                    "type" => $item->eventReminderType,
                    "count" => $item->eventReminderCount
                );
                
                $eventMemberArr = array(
                    "eventResponse" => $item->eventResponse,
                    "eventUserSlug" => $item->eventUserSlug,
                    "eventUserName" => $item->eventUserName,
                    "eventUserEmail" => $item->eventUserEmail
                );
                if(!empty($item->eventUserSlug)){
                    array_push($eventMembers, $eventMemberArr);
                }
            });

            $responseArr['eventMembers'] = $eventMembers;

        } catch (\Exception $e) {
            $errmsg = $e->getMessage();
            $content = array();
            $content['error']   = array("msg"=>$errmsg);
            $content['code']    =  422;
            $content['status']  = "ERROR";
            return $content;
        }

        $content = array();
        $content['data'] = $responseArr;
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
}