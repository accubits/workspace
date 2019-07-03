<?php

namespace Modules\SocialModule\Repositories\Announcement;

use Modules\SocialModule\Repositories\AnnouncementRepositoryInterface;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\SocialModule\Entities\SocialAnnouncementUserMap;
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
use Modules\SocialModule\Entities\SocialActivityStreamAnnouncementMap;
use Carbon\Carbon;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{

    private $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    /*
     * create, update and delete Announcement
     */
    public function createAnnouncement($request)
    {
        
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
            if($request->action == 'create'){

                $announcement   = new SocialAnnouncement();
                $announcement->{SocialAnnouncement::slug} = Utilities::getUniqueId();
                $announcement->{SocialAnnouncement::title} = $request->ancTitle;
                $announcement->{SocialAnnouncement::description}  = $request->ancDesc;

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $announcement->{SocialAnnouncement::org_id}  = $organisationObj->id;
                $announcement->{SocialAnnouncement::creator_user_id}  = $user->id;
                $announcement->{SocialAnnouncement::is_announcement_to_all}  = $request->toAllEmployee;
                $announcement->save();
                
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $announcement, "Announcement created");
                $toObj = collect($request->toUsers);
                $this->setAnnouncementUserMapObjs($toObj, $announcement, $socialActivityStreamMaster);

            } else if($request->action == 'update'){
                $announcement = SocialAnnouncement::where(SocialAnnouncement::slug, '=',$request->ancSlug)
                        ->first();
                if(empty($announcement)){
                    throw new \Exception("Invalid Announcement");
                }
                $announcement->{SocialAnnouncement::title} = $request->ancTitle;
                $announcement->{SocialAnnouncement::description}  = $request->ancDesc;

                $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $announcement->{SocialAnnouncement::org_id}  = $organisationObj->id;
                $announcement->{SocialAnnouncement::creator_user_id}  = $user->id;
                $announcement->{SocialAnnouncement::is_announcement_to_all}  = $request->toAllEmployee;
                $announcement->save();
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $announcement, "Announcement updated");
                $toObj = collect($request->toUsers);
                $this->setAnnouncementUserMapObjs($toObj, $announcement, $socialActivityStreamMaster);
                
            } else if($request->action == 'delete'){

                $announcement = DB::table(SocialAnnouncement::table)
                        ->where(SocialAnnouncement::slug, '=',$request->ancSlug)
                        ->first();
                if(empty($announcement)){
                    throw new \Exception("Invalid Announcement");
                }
                
                $socialActivityStreamAncMaps = DB::table(SocialActivityStreamAnnouncementMap::table)
                ->where(SocialActivityStreamAnnouncementMap::annoucement_id, '=', $announcement->id)->get();
                
                $socialActivityStreamAncMaps->each(function($singleSocialActivityStreamAncMap){
                    DB::table(SocialActivityStreamMaster::table)
                    ->where("id", '=', $singleSocialActivityStreamAncMap->{SocialActivityStreamAnnouncementMap::activity_stream_master_id})
                    ->delete();
                });

                DB::table(SocialAnnouncement::table)
                ->where(SocialAnnouncement::slug, '=', $request->ancSlug)
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
            $content['data']['msg'] = "Annoucement created successfully";
        } else if($request->action == "update"){
            $content['data']['msg'] = "Annoucement updated successfully";
        } else if($request->action == "delete"){
            $content['data']['msg'] = "Annoucement deleted successfully";
        }
        $content['data']["ancSlug"]=$announcement->{SocialAnnouncement::slug};
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    public function setAnnouncementUserMapObjs($toObj, $announcement, $socialActivityStreamMaster) {
        
        $toUserIdArr = array();
        $toObj->each(function ($item) use ($announcement, &$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleSocialAnnouncementUserMap($item, $announcement, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialAnnouncementUserMap::table)
        ->where(SocialAnnouncementUserMap::social_announcement_id, '=', $announcement->id)
        ->whereNotIn(SocialAnnouncementUserMap::user_id, $toUserIdArr)
        ->delete();
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }

    public function setSingleSocialAnnouncementUserMap($item, $announcement, $socialActivityStreamMaster) {
        
        $toUser = $this->commonRepository->getUser($item);
        
        $socialAncUserMapObj = SocialAnnouncementUserMap::
                where(SocialAnnouncementUserMap::social_announcement_id, '=',$announcement->id)
                ->where(SocialAnnouncementUserMap::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialAncUserMapObj)){
            $socialAncUserMapObj = new SocialAnnouncementUserMap;
            $socialAncUserMapObj->{SocialAnnouncementUserMap::user_id} = $toUser->id ;
            $socialAncUserMapObj->{SocialAnnouncementUserMap::social_announcement_id} = $announcement->id;
            $socialAncUserMapObj->{SocialAnnouncementUserMap::mark_as_read} = false;
            $socialAncUserMapObj->{SocialAnnouncementUserMap::read_datetime} = null;
            $socialAncUserMapObj->save();
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
        return $socialAncUserMapObj->{SocialAnnouncementUserMap::user_id};
    }

    public function setSocialActivityStream($user,$organisationObj,$announcement, $note) {
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"announcement")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $user->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterAncMap = DB::table(SocialActivityStreamAnnouncementMap::table)
                ->where(SocialActivityStreamAnnouncementMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamAnnouncementMap::annoucement_id, '=',$announcement->id)
                ->first();

        if(empty($socialActivityStreamMasterAncMap)){
            
            $this->updateOldAnnouncementActivityStream($announcement);
            $socialActivityStreamMasterAncMap = new SocialActivityStreamAnnouncementMap;
            $socialActivityStreamMasterAncMap->{SocialActivityStreamAnnouncementMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterAncMap->{SocialActivityStreamAnnouncementMap::annoucement_id} = $announcement->id;
            $socialActivityStreamMasterAncMap->save();
        }
        return $socialActivityStreamMaster;
    }
    
    private function updateOldAnnouncementActivityStream($announcement) {

        $socialActivityStreamMasterMappingCheckMap = DB::table(SocialActivityStreamAnnouncementMap::table)
        ->where(SocialActivityStreamAnnouncementMap::annoucement_id, '=',$announcement->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamAnnouncementMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }

    public function setAnnouncementReadStatus($request) {
       
        $user = Auth::user();
        DB::beginTransaction();
        try {
        $announcement = SocialAnnouncement::where(SocialAnnouncement::slug, '=',$request->ancSlug)
                        ->first();
        if(empty($announcement)){
            throw new \Exception("Invalid Announcement");
        }

        $socialAncUserMapObj = SocialAnnouncementUserMap::
                where(SocialAnnouncementUserMap::social_announcement_id, '=',$announcement->id)
                ->where(SocialAnnouncementUserMap::user_id, '=',$user->id)
                ->first();

        //if is_announcement_to_all (true) then add to SocialAnnouncementUserMap
        if(empty($socialAncUserMapObj) && $announcement->{SocialAnnouncement::is_announcement_to_all}){ 
            $socialAncUserMapObj = new SocialAnnouncementUserMap();
            $socialAncUserMapObj->{SocialAnnouncementUserMap::social_announcement_id} = $announcement->id;
            $socialAncUserMapObj->{SocialAnnouncementUserMap::user_id} = $user->id;
        }

        if(empty($socialAncUserMapObj)){
            throw new \Exception("Invalid AnnouncementReadStatus update");
        }

        $socialAncUserMapObj->{SocialAnnouncementUserMap::mark_as_read} = TRUE;
        $socialAncUserMapObj->{SocialAnnouncementUserMap::read_datetime} = Carbon::now();
        $socialAncUserMapObj->save();
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
        $content['data'] = array("msg"=>"Announcement read status updated ");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
}