<?php

namespace Modules\SocialModule\Repositories\Message;

use Modules\SocialModule\Repositories\MessageRepositoryInterface;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialMessageUserMap;
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
use Modules\SocialModule\Entities\SocialActivityStreamMessageMap;
use Carbon\Carbon;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{

    private $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    /*
     * create, update and delete message
     */
    public function createMessage($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
            if($request->action == 'create'){

                $message   = new SocialMessage();
                $message->{SocialMessage::slug} = Utilities::getUniqueId();
                $message->{SocialMessage::title} = $request->msgTitle;
                $message->{SocialMessage::description}  = $request->msgDesc;

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $message->{SocialMessage::org_id}  = $organisationObj->id;
                $message->{SocialMessage::creator_user_id}  = $user->id;
                $message->{SocialMessage::is_message_to_all}  = $request->toAllEmployee;
                $message->save();
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $message, "Message created");
                $toObj = collect($request->toUsers);
                
                $this->setMessageUserMapObjs($toObj, $message, $socialActivityStreamMaster);

            } else if($request->action == 'update'){
                $message = SocialMessage::where(SocialMessage::slug, '=',$request->msgSlug)
                        ->first();
                if(empty($message)){
                    throw new \Exception("Invalid Message");
                }
                $message->{SocialMessage::title} = $request->msgTitle;
                $message->{SocialMessage::description}  = $request->msgDesc;

                $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $message->{SocialMessage::org_id}  = $organisationObj->id;
                $message->{SocialMessage::creator_user_id}  = $user->id;
                $message->{SocialMessage::is_message_to_all}  = $request->toAllEmployee;
                $message->save();
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $message, "Message updated");
                $toObj = collect($request->toUsers);
                $this->setMessageUserMapObjs($toObj, $message, $socialActivityStreamMaster);
                
            } else if($request->action == 'delete'){

                $message = DB::table(SocialMessage::table)
                        ->where(SocialMessage::slug, '=',$request->msgSlug)
                        ->first();
                if(empty($message)){
                    throw new \Exception("Invalid Message");
                }
                
                $socialActivityStreamMsgMaps = DB::table(SocialActivityStreamMessageMap::table)
                ->where(SocialActivityStreamMessageMap::message_id, '=', $message->id)->get();
                
                $socialActivityStreamMsgMaps->each(function($singleSocialActivityStreamMsgMap){
                    DB::table(SocialActivityStreamMaster::table)
                    ->where("id", '=', $singleSocialActivityStreamMsgMap->{SocialActivityStreamMessageMap::activity_stream_master_id})
                    ->delete();
                });

                DB::table(SocialMessage::table)
                ->where(SocialMessage::slug, '=', $request->msgSlug)
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
            $content['data']['msg'] = "Message created successfully";
        } else if($request->action == "update"){
            $content['data']['msg'] = "Message updated successfully";
        } else if($request->action == "delete"){
            $content['data']['msg'] = "Message deleted successfully";
        }
        $content['data']["msgSlug"] = $message->{SocialMessage::slug};
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    public function setMessageUserMapObjs($toObj, $message, $socialActivityStreamMaster) {

        $toUserIdArr = array();
        $toObj->each(function ($item) use ($message, &$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleSocialMessageUserMap($item, $message, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialMessageUserMap::table)
        ->where(SocialMessageUserMap::social_message_id, '=', $message->id)
        ->whereNotIn(SocialMessageUserMap::user_id, $toUserIdArr)
        ->delete();
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }

    public function setSingleSocialMessageUserMap($item, $message, $socialActivityStreamMaster) {
        
        $toUser = $this->commonRepository->getUser($item);
        
        $socialMsgUserMapObj = SocialMessageUserMap::
                where(SocialMessageUserMap::social_message_id, '=',$message->id)
                ->where(SocialMessageUserMap::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialMsgUserMapObj)){
            $socialMsgUserMapObj = new SocialMessageUserMap;
            $socialMsgUserMapObj->{SocialMessageUserMap::user_id} = $toUser->id ;
            $socialMsgUserMapObj->{SocialMessageUserMap::social_message_id} = $message->id;
            $socialMsgUserMapObj->{SocialMessageUserMap::read_status} = false;
            $socialMsgUserMapObj->{SocialMessageUserMap::read_datetime} = null;
            $socialMsgUserMapObj->save();
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
        return $socialMsgUserMapObj->{SocialMessageUserMap::user_id};
    }

    public function setSocialActivityStream($user,$organisationObj,$message, $note) {
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"message")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $user->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterMsgMap = DB::table(SocialActivityStreamMessageMap::table)
                ->where(SocialActivityStreamMessageMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamMessageMap::message_id, '=',$message->id)
                ->first();

        if(empty($socialActivityStreamMasterMsgMap)){
            
            $this->updateOldMessageActivityStream($message);
            $socialActivityStreamMasterMsgMap = new SocialActivityStreamMessageMap;
            $socialActivityStreamMasterMsgMap->{SocialActivityStreamMessageMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterMsgMap->{SocialActivityStreamMessageMap::message_id} = $message->id;
            $socialActivityStreamMasterMsgMap->save();
        }
        return $socialActivityStreamMaster;
    }

    private function updateOldMessageActivityStream($message) {

        $socialActivityStreamMasterMappingCheckMap = DB::table(SocialActivityStreamMessageMap::table)
        ->where(SocialActivityStreamMessageMap::message_id, '=',$message->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamMessageMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }

}