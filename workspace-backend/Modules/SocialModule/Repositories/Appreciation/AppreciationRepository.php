<?php

namespace Modules\SocialModule\Repositories\Appreciation;

use Modules\SocialModule\Repositories\AppreciationRepositoryInterface;
use Modules\SocialModule\Entities\SocialAppreciation;
use Modules\SocialModule\Entities\SocialAppreciationRecipientMap;
use Modules\SocialModule\Entities\SocialAppreciationUserMap;
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
use Modules\SocialModule\Entities\SocialActivityStreamAppreciationMap;
use Carbon\Carbon;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class AppreciationRepository implements AppreciationRepositoryInterface
{

    private $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    /*
     * create, update and delete appreciation
     */
    public function createAppreciation($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
            if($request->action == 'create'){

                $appreciationObj   = new SocialAppreciation();
                $appreciationObj->{SocialAppreciation::slug} = Utilities::getUniqueId();
                $appreciationObj->{SocialAppreciation::title} = $request->aprTitle;
                $appreciationObj->{SocialAppreciation::description}  = $request->aprDesc;

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation!");
                }
                $appreciationObj->{SocialAppreciation::org_id}  = $organisationObj->id;
                $appreciationObj->{SocialAppreciation::creator_user_id}  = $user->id;
                $appreciationObj->{SocialAppreciation::notify_appreciation_to_all} = $request->toAllEmployee;
                
                if(!in_array($request->status, array("Show","Hide"))){
                    throw new \Exception("Invalid Status ".$request->status." found");
                }
                $aprStatusLookUpId = $this->commonRepository->getLookupId("appreciation", "status", $request->status);
                
                $appreciationObj->{SocialAppreciation::status_id} = $aprStatusLookUpId;
                
                if(!empty($request->aprHasDisplayDuration)){
                    $appreciationObj->{SocialAppreciation::has_duration} = $request->aprHasDisplayDuration;
                    $appreciationObj->{SocialAppreciation::duration_start} = !empty($request->aprDisplayStart) ? date('Y-m-d H:i:s', $request->aprDisplayStart):null;
                    $appreciationObj->{SocialAppreciation::duration_end} = !empty($request->aprDisplayEnd) ? date('Y-m-d H:i:s', $request->aprDisplayEnd):null;
                } else {
                    $appreciationObj->{SocialAppreciation::has_duration} = false;
                    $appreciationObj->{SocialAppreciation::duration_start} = null;
                    $appreciationObj->{SocialAppreciation::duration_end} = null;
                }

                $appreciationObj->save();
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $appreciationObj, "Appreciation created");
                $toObj = collect($request->toUsers);
                $recipientsObj = collect($request->recipients);

                $this->setAppreciationUserMapObjs($toObj, $recipientsObj, $appreciationObj, $socialActivityStreamMaster);

            } else if($request->action == 'update'){
                $appreciationObj = SocialAppreciation::where(SocialAppreciation::slug, '=',$request->aprSlug)
                        ->first();
                if(empty($appreciationObj)){
                    throw new \Exception("Invalid Appreciation");
                }
                $appreciationObj->{SocialAppreciation::title} = $request->aprTitle;
                $appreciationObj->{SocialAppreciation::description}  = $request->aprDesc;

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $appreciationObj->{SocialAppreciation::org_id}  = $organisationObj->id;
                $appreciationObj->{SocialAppreciation::creator_user_id}  = $user->id;
                $appreciationObj->{SocialAppreciation::notify_appreciation_to_all} = $request->toAllEmployee;
                
                if(!in_array($request->status, array("Show","Hide"))){
                    throw new \Exception("Invalid Status ".$request->status." found");
                }
                $aprStatusLookUpId = $this->commonRepository->getLookupId("appreciation", "status", $request->status);
                
                $appreciationObj->{SocialAppreciation::status_id} = $aprStatusLookUpId;
                
                if(!empty($request->aprHasDisplayDuration)){
                    $appreciationObj->{SocialAppreciation::has_duration} = $request->aprHasDisplayDuration;
                    $appreciationObj->{SocialAppreciation::duration_start} = !empty($request->aprDisplayStart) ? date('Y-m-d H:i:s', $request->aprDisplayStart):null;
                    $appreciationObj->{SocialAppreciation::duration_end} = !empty($request->aprDisplayEnd) ? date('Y-m-d H:i:s', $request->aprDisplayEnd):null;
                } else {
                    $appreciationObj->{SocialAppreciation::has_duration} = false;
                    $appreciationObj->{SocialAppreciation::duration_start} = null;
                    $appreciationObj->{SocialAppreciation::duration_end} = null;
                }

                $appreciationObj->save();
                //activityStream
                $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $appreciationObj, "Appreciation updated");
                $toObj = collect($request->toUsers);
                $recipientsObj = collect($request->recipients);

                $this->setAppreciationUserMapObjs($toObj, $recipientsObj, $appreciationObj, $socialActivityStreamMaster);
                
            } else if($request->action == 'delete'){

                $appreciationObj = SocialAppreciation::where(SocialAppreciation::slug, '=',$request->aprSlug)
                        ->first();
                if(empty($appreciationObj)){
                    throw new \Exception("Invalid Appreciation");
                }
                
                $socialActivityStreamAprMaps = DB::table(SocialActivityStreamAppreciationMap::table)
                ->where(SocialActivityStreamAppreciationMap::appreciation_id, '=', $appreciationObj->id)->get();
                
                $socialActivityStreamAprMaps->each(function($socialActivityStreamAprMap){
                    DB::table(SocialActivityStreamMaster::table)
                    ->where("id", '=', $socialActivityStreamAprMap->{SocialActivityStreamAppreciationMap::activity_stream_master_id})
                    ->delete();
                });

                SocialAppreciation::where(SocialAppreciation::slug, '=',$request->aprSlug)
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
            $content['data']['msg'] = "Appreciation created successfully";
        } else if($request->action == "update"){
            $content['data']['msg'] = "Appreciation updated successfully";
        } else if($request->action == "delete"){
            $content['data']['msg'] = "Appreciation deleted successfully";
        }
        $content['data']["aprSlug"] = $appreciationObj->{SocialAppreciation::slug};
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    public function setAppreciationUserMapObjs($toObj, $recipientsObj, $appreciation, $socialActivityStreamMaster) {

        $toUserIdArr = array();
        $toObj->each(function ($item) use ($appreciation, &$toUserIdArr, $socialActivityStreamMaster) {
            array_push($toUserIdArr, $this->setSingleSocialAppreciationUserMap($item, $appreciation, $socialActivityStreamMaster));
        });
        
        $recipientsUserIdArr = array();
        $recipientsObj->each(function ($item) use ($appreciation, &$recipientsUserIdArr, $socialActivityStreamMaster) {
            array_push($recipientsUserIdArr, $this->setSingleSocialAppreciationRecipientMap($item, $appreciation, $socialActivityStreamMaster));
        });
        //delete all other  if any
        DB::table(SocialAppreciationUserMap::table)
        ->where(SocialAppreciationUserMap::appreciation_id, '=', $appreciation->id)
        ->whereNotIn(SocialAppreciationUserMap::user_id, $toUserIdArr)
        ->delete();
        //delete all other  if any
        DB::table(SocialAppreciationRecipientMap::table)
        ->where(SocialAppreciationRecipientMap::appreciation_id, '=', $appreciation->id)
        ->whereNotIn(SocialAppreciationRecipientMap::user_id, $recipientsUserIdArr)
        ->delete();
        
        $activityStreamUserArr = array_merge($toUserIdArr, $recipientsUserIdArr);
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $activityStreamUserArr)
        ->delete();
    }

    public function setSingleSocialAppreciationUserMap($item, $appreciation, $socialActivityStreamMaster) {

        $toUser = $this->commonRepository->getUser($item);
        
        $socialAprUserMapObj = SocialAppreciationUserMap::
                where(SocialAppreciationUserMap::appreciation_id, '=',$appreciation->id)
                ->where(SocialAppreciationUserMap::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialAprUserMapObj)){
            $socialAprUserMapObj = new SocialAppreciationUserMap;
            $socialAprUserMapObj->{SocialAppreciationUserMap::user_id} = $toUser->id ;
            $socialAprUserMapObj->{SocialAppreciationUserMap::appreciation_id} = $appreciation->id;
            $socialAprUserMapObj->{SocialAppreciationUserMap::mark_as_read} = false;
            $socialAprUserMapObj->{SocialAppreciationUserMap::read_datetime} = null;
            $socialAprUserMapObj->save();
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
        return $socialAprUserMapObj->{SocialAppreciationUserMap::user_id};
    }

    public function setSingleSocialAppreciationRecipientMap($item, $appreciation, $socialActivityStreamMaster) {

        $toUser = $this->commonRepository->getUser($item);
        
        $socialAprUserMapObj = SocialAppreciationRecipientMap::
                where(SocialAppreciationRecipientMap::appreciation_id, '=',$appreciation->id)
                ->where(SocialAppreciationRecipientMap::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialAprUserMapObj)){
            $socialAprUserMapObj = new SocialAppreciationRecipientMap;
            $socialAprUserMapObj->{SocialAppreciationRecipientMap::user_id} = $toUser->id ;
            $socialAprUserMapObj->{SocialAppreciationRecipientMap::appreciation_id} = $appreciation->id;
            $socialAprUserMapObj->{SocialAppreciationRecipientMap::mark_as_read} = false;
            $socialAprUserMapObj->{SocialAppreciationRecipientMap::read_datetime} = null;
            $socialAprUserMapObj->save();
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
        return $socialAprUserMapObj->{SocialAppreciationUserMap::user_id};
    }

    public function setSocialActivityStream($user,$organisationObj,$appreciation, $note) {
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"appreciation")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $user->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterAprMap = DB::table(SocialActivityStreamAppreciationMap::table)
                ->where(SocialActivityStreamAppreciationMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamAppreciationMap::appreciation_id, '=',$appreciation->id)
                ->first();

        if(empty($socialActivityStreamMasterAprMap)){
            
            $this->updateOldAppreciationActivityStream($appreciation);
            $socialActivityStreamMasterAprMap = new SocialActivityStreamAppreciationMap;
            $socialActivityStreamMasterAprMap->{SocialActivityStreamAppreciationMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterAprMap->{SocialActivityStreamAppreciationMap::appreciation_id} = $appreciation->id;
            $socialActivityStreamMasterAprMap->save();
        }
        return $socialActivityStreamMaster;
    }
    
    private function updateOldAppreciationActivityStream($appreciation) {

        $socialActivityStreamMasterMappingCheckMap = DB::table(SocialActivityStreamAppreciationMap::table)
        ->where(SocialActivityStreamAppreciationMap::appreciation_id, '=',$appreciation->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamAppreciationMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }

}