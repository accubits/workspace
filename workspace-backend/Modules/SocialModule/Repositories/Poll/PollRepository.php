<?php

namespace Modules\SocialModule\Repositories\Poll;

use Modules\SocialModule\Repositories\PollRepositoryInterface;
use Modules\SocialModule\Entities\SocialPollGroup;
use Modules\SocialModule\Entities\SocialPoll;
use Modules\SocialModule\Entities\SocialPollOptions;
use Modules\SocialModule\Entities\SocialPollInvitedUsers;
use Modules\SocialModule\Entities\SocialPollUserAnswers;
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
use Modules\SocialModule\Entities\SocialActivityStreamPollMap;
use Carbon\Carbon;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;

class PollRepository implements PollRepositoryInterface
{

    private $commonRepository;
    public function __construct(CommonRepositoryInterface $commonRepository)
    {
        $this->commonRepository = $commonRepository;
    }

    /*
     * create, update and delete poll
     */
    public function createPoll($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
            if($request->action == 'create'){

                $pollGroup   = new SocialPollGroup();
                $pollGroup->{SocialPollGroup::slug} = Utilities::getUniqueId();
                $this->setPollFactory($pollGroup, $request, $user, "Poll created");

            } else if($request->action == 'update'){
                $pollGroup = SocialPollGroup::where(SocialPollGroup::slug, '=',$request->pollSlug)
                        ->first();
                if(empty($pollGroup)){
                    throw new \Exception("Invalid Poll");
                }
                $this->setPollFactory($pollGroup, $request, $user, "Poll Updated");

            } else if($request->action == 'delete'){

                $pollGroup = DB::table(SocialPollGroup::table)
                        ->where(SocialPollGroup::slug, '=',$request->pollSlug)
                        ->first();
                if(empty($pollGroup)){
                    throw new \Exception("Invalid Poll");
                }
                
                $socialActivityStreamPollMaps = DB::table(SocialActivityStreamPollMap::table)
                ->where(SocialActivityStreamPollMap::poll_group_id, '=', $pollGroup->id)->get();
                
                $socialActivityStreamPollMaps->each(function($singleSocialActivityStreamPollMap){
                    DB::table(SocialActivityStreamMaster::table)
                    ->where("id", '=', $singleSocialActivityStreamPollMap->{SocialActivityStreamPollMap::activity_stream_master_id})
                    ->delete();
                });

                DB::table(SocialPollGroup::table)
                ->where(SocialPollGroup::slug, '=', $request->pollSlug)
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
            $content['data']['msg'] = "Poll created successfully";
        } else if($request->action == "update"){
            $content['data']['msg'] = "Poll updated successfully";
        } else if($request->action == "delete"){
            $content['data']['msg'] = "Poll deleted successfully";
        }
        $content['data']["pollSlug"] = $pollGroup->{SocialPollGroup::slug};
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    
    public function setPollStatus($request) {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            
                $pollGroup = SocialPollGroup::where(SocialPollGroup::slug, '=',$request->pollSlug)
                        ->first();
                if(empty($pollGroup)){
                    throw new \Exception("Invalid Poll");
                }
                $pollGroupStatusLookUpId = $this->commonRepository->getLookupId("poll", "status", 
                          !empty($request->status)  ? $request->status : null
                        );
                if(empty($pollGroupStatusLookUpId)){
                    throw new \Exception("Invalid Poll Status");
                }
                $pollGroup->{SocialPollGroup::status_id}  = $pollGroupStatusLookUpId;

                $pollGroup->save();

            DB::commit();
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
        $content['data'] = array("msg"=> "Poll status updated!");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    
    private function setPollFactory(&$pollGroup, $request, $user, $activityNote){
        $pollGroup->{SocialPollGroup::poll_title} = $request->pollTitle;
        $pollGroup->{SocialPollGroup::poll_description}  = $request->pollDesc;
        $pollGroup->{SocialPollGroup::is_poll_to_all}  = $request->toAllEmployee;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $pollGroup->{SocialPollGroup::org_id}  = $organisationObj->id;
        $pollGroup->{SocialPollGroup::creator_user_id}  = $user->id;

        $pollGroupStatusLookUpId = $this->commonRepository->getLookupId("poll", "status", 
                  !empty($request->status)  ? $request->status : null
                );
        $pollGroup->{SocialPollGroup::status_id}  = $pollGroupStatusLookUpId;

        $pollGroup->save();
        
        $pollQuestionInputArr = $request->pollQuestions;
 
        $this->setPollQuestions($pollQuestionInputArr, $pollGroup, $organisationObj, $user);

        //activityStream
        $socialActivityStreamMaster = $this->setSocialActivityStream($user, $organisationObj, $pollGroup, $activityNote);
        $toObj = collect($request->toUsers);

        $this->setPollUserMapObjs($toObj, $pollGroup, $socialActivityStreamMaster, $organisationObj);
    }
    
    private function setPollQuestions($pollQuestionInputArr, $pollGroupObj, $orgObj, $user){
        $savedPollIdArr = array(); 
        foreach ($pollQuestionInputArr as $pollQuestionInput) {
            if($pollQuestionInput['action']=="create"){
                $poll = new SocialPoll;
                $poll->{SocialPoll::slug} = Utilities::getUniqueId();
                $poll->{SocialPoll::poll_group_id} = $pollGroupObj->id;
                $poll->{SocialPoll::org_id} = $orgObj->id;
                $poll->{SocialPoll::question} = $pollQuestionInput['pollQuestion'];
                $poll->{SocialPoll::allow_multiple_choice} = $pollQuestionInput['allowMultipleChoice'];
                $poll->{SocialPoll::creator_user_id} = $user->id;
                $poll->save();
                
                array_push($savedPollIdArr, $poll->id);
                
                $this->setPollOptions($pollQuestionInput['answerOptions'], $poll, $orgObj, $user);
                
            } else if($pollQuestionInput['action']=="update"){
                $poll = SocialPoll::where('id', '=',$pollQuestionInput['pollQuestionId'])
                        ->first();
                if(empty($poll)){
                    throw new \Exception("Invalid pollQuestionId");
                }
                $poll->{SocialPoll::poll_group_id} = $pollGroupObj->id;
                $poll->{SocialPoll::org_id} = $orgObj->id;
                $poll->{SocialPoll::question} = $pollQuestionInput['pollQuestion'];
                $poll->{SocialPoll::allow_multiple_choice} = $pollQuestionInput['allowMultipleChoice'];
                $poll->{SocialPoll::creator_user_id} = $user->id;
                $poll->save();
                
                array_push($savedPollIdArr, $poll->id);

                $this->setPollOptions($pollQuestionInput['answerOptions'], $poll, $orgObj, $user);                
            }
        }
        //delete all other  if any under given pollgroup
        DB::table(SocialPoll::table)
        ->where(SocialPoll::poll_group_id, '=', $pollGroupObj->id)
        ->whereNotIn('id', $savedPollIdArr)
        ->delete();
    }
    
    private function setPollOptions($pollAnswerOptionsArr, $poll, $orgObj, $user){
        $savedPollOptIdArr = array();
        foreach ($pollAnswerOptionsArr as $pollAnswerOptions) {
            if(empty($pollAnswerOptions['pollOptId'])){
                $pollOpt = new SocialPollOptions();
                $pollOpt->{SocialPollOptions::poll_id} = $poll->id;
                $pollOpt->{SocialPollOptions::poll_answeroption} = $pollAnswerOptions['pollOption'];
                $pollOpt->{SocialPollOptions::org_id} = $orgObj->id;
                $pollOpt->{SocialPollOptions::creator_user_id} = $user->id;
                $pollOpt->save();
                array_push($savedPollOptIdArr, $pollOpt->id);

            } else {
                $pollOpt = SocialPollOptions::where("id", '=',$pollAnswerOptions['pollOptId'])
                ->first();
                if(empty($pollOpt)){
                    throw new \Exception("Invalid pollOptId ".$pollAnswerOptions['pollOptId']);
                }
                $pollOpt->{SocialPollOptions::poll_id} = $poll->id;
                $pollOpt->{SocialPollOptions::poll_answeroption} = $pollAnswerOptions['pollOption'];
                $pollOpt->{SocialPollOptions::org_id} = $orgObj->id;
                $pollOpt->{SocialPollOptions::creator_user_id} = $user->id;
                $pollOpt->save();
                array_push($savedPollOptIdArr, $pollOpt->id);
            }
        }
        
        //delete all other  if any under given poll
        DB::table(SocialPollOptions::table)
        ->where(SocialPollOptions::poll_id, '=', $poll->id)
        ->whereNotIn('id', $savedPollOptIdArr)
        ->delete();
        
        return $savedPollOptIdArr;
    }

    public function setPollUserMapObjs($toObj, $pollGroup, $socialActivityStreamMaster, $orgObj) {

        $toUserIdArr = array();
        $toObj->each(function ($item) use ($pollGroup, &$toUserIdArr, $socialActivityStreamMaster, $orgObj) {
            array_push($toUserIdArr, $this->setSingleSocialPollUserMap($item, $pollGroup, $socialActivityStreamMaster, $orgObj));
        });
        //delete all other  if any
        DB::table(SocialPollInvitedUsers::table)
        ->where(SocialPollInvitedUsers::poll_group_id, '=', $pollGroup->id)
        ->whereNotIn(SocialPollInvitedUsers::user_id, $toUserIdArr)
        ->delete();
        DB::table(SocialActivityStreamUser::table)
        ->where(SocialActivityStreamUser::activity_stream_master_id, '=', $socialActivityStreamMaster->id)
        ->whereNotIn(SocialActivityStreamUser::to_user_id, $toUserIdArr)
        ->delete();
    }

    public function setSingleSocialPollUserMap($item, $pollGroup, $socialActivityStreamMaster, $orgObj) {

        $toUser = $this->commonRepository->getUser($item);

        $socialPollInvitedUsersObj = SocialPollInvitedUsers::
                where(SocialPollInvitedUsers::poll_group_id, '=',$pollGroup->id)
                ->where(SocialPollInvitedUsers::user_id, '=',$toUser->id)
                ->first();
        if(empty($socialPollInvitedUsersObj)){
            $socialPollInvitedUsersObj = new SocialPollInvitedUsers;
            $socialPollInvitedUsersObj->{SocialPollInvitedUsers::user_id} = $toUser->id ;
            $socialPollInvitedUsersObj->{SocialPollInvitedUsers::poll_group_id} = $pollGroup->id;
            $socialPollInvitedUsersObj->{SocialPollInvitedUsers::org_id} = $orgObj->id;
            $socialPollInvitedUsersObj->save();
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
        return $socialPollInvitedUsersObj->{SocialPollInvitedUsers::user_id};
    }

    public function setSocialActivityStream($user,$organisationObj,$pollGroup, $note) {
        $activityStreamTypeObj = DB::table(SocialActivityStreamType::table)
                ->where(SocialActivityStreamType::name, '=',"poll")
                ->first();

        $socialActivityStreamMaster = new SocialActivityStreamMaster;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::activity_stream_type_id} = $activityStreamTypeObj->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::from_user_id} = $user->id;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::stream_datetime} = Carbon::now();
        $socialActivityStreamMaster->{SocialActivityStreamMaster::note} = $note;
        $socialActivityStreamMaster->{SocialActivityStreamMaster::org_id} = $organisationObj->id;
        $socialActivityStreamMaster->save();

        $socialActivityStreamMasterPollMap = DB::table(SocialActivityStreamPollMap::table)
                ->where(SocialActivityStreamPollMap::activity_stream_master_id, '=',$socialActivityStreamMaster->id)
                ->where(SocialActivityStreamPollMap::poll_group_id, '=',$pollGroup->id)
                ->first();

        if(empty($socialActivityStreamMasterPollMap)){
            
            $this->updateOldPollActivityStream($pollGroup);
            $socialActivityStreamMasterPollMap = new SocialActivityStreamPollMap;
            $socialActivityStreamMasterPollMap->{SocialActivityStreamPollMap::activity_stream_master_id} = $socialActivityStreamMaster->id;
            $socialActivityStreamMasterPollMap->{SocialActivityStreamPollMap::poll_group_id} = $pollGroup->id;
            $socialActivityStreamMasterPollMap->save();
        }
        return $socialActivityStreamMaster;
    }
    
    private function updateOldPollActivityStream($pollGroup) {

        $socialActivityStreamMasterMappingCheckMap = DB::table(SocialActivityStreamPollMap::table)
        ->where(SocialActivityStreamPollMap::poll_group_id, '=',$pollGroup->id)
        ->get();
        
        $activityStreamMasterIDsArr=array();
        $socialActivityStreamMasterMappingCheckMap->each(function($kitem) use(&$activityStreamMasterIDsArr){
            array_push($activityStreamMasterIDsArr, $kitem->{SocialActivityStreamPollMap::activity_stream_master_id});
        });
        
        //update old activity stream to hidden
        DB::table(SocialActivityStreamMaster::table)
                ->whereIn('id', $activityStreamMasterIDsArr)
                ->update([SocialActivityStreamMaster::is_hidden=>TRUE]);
    }
    
    /*
     * setPollAnswers
     */
    public function setPollAnswers($request) {
        
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $org = Organization::where(Organization::slug, '=',$request->orgSlug)
                            ->first();
            if(empty($org)){
                throw new \Exception("Invalid orgSlug");
            }
            $pollGroup = SocialPollGroup::where(SocialPollGroup::slug, '=',$request->pollSlug)
                    ->where(SocialPollGroup::org_id, '=',$org->id)
                            ->first();
            if(empty($pollGroup)){
                throw new \Exception("Invalid Poll");
            }

            $pollGroupClosedStatusLookUpId = $this->commonRepository->getLookupId("poll", "status", 
                          "Closed"
                        );
            if(empty($pollGroupClosedStatusLookUpId)){
                throw new \Exception("Invalid Poll Status Closed");
            }
            //check if poll is closed
            if( $pollGroupClosedStatusLookUpId == $pollGroup->{SocialPollGroup::status_id} ){
                throw new \Exception("Sorry, this poll has been closed!");
            }
            
            $pollAnswersArr = $request->pollQuestionsAnswers;
            if(!is_array($pollAnswersArr) || empty($pollAnswersArr)){
                throw new \Exception("Invalid pollQuestionsAnswers, pollQuestionsAnswers should be an array");
            }

            foreach ($pollAnswersArr as $pollAnswers) {
                $this->setSinglePollUserAnswer($pollAnswers,$org,$user);
            }
            
            DB::commit();
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
        $content['data'] = array("msg"=> "Poll answer submitted successfully");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;

    }
    
    private function setSinglePollUserAnswer($pollAnswers,$org,$user){

        
        $poll = SocialPoll::where('id', '=', $pollAnswers['pollQuestionId'])
                ->first();
        if(empty($poll)){
            throw new \Exception("Invalid pollQuestionId");
        }

        $selectedAnswerOptionsArr = $pollAnswers['selectedAnswerOptions'];

        
        $selectedPollUserAnswerIDArr = array();
        foreach ($selectedAnswerOptionsArr as $optArr) {

            $socialPollOptions = SocialPollOptions::where(SocialPollOptions::poll_id, '=',$poll->id)
                    ->where('id', '=',$optArr['pollOptId'])
                            ->first();
            if(empty($socialPollOptions)){
                throw new \Exception("Invalid pollOptId");
            }            
            $socialPollUserAnswers = new SocialPollUserAnswers();
            $socialPollUserAnswers->{SocialPollUserAnswers::org_id} = $org->id;
            $socialPollUserAnswers->{SocialPollUserAnswers::poll_id} = $poll->id;
            $socialPollUserAnswers->{SocialPollUserAnswers::poll_option_id} = $optArr['pollOptId'];
            $socialPollUserAnswers->{SocialPollUserAnswers::user_id}=$user->id;
            $socialPollUserAnswers->save();
            array_push($selectedPollUserAnswerIDArr, $socialPollUserAnswers->id);
        }

        //delete previous user answer id any
        SocialPollUserAnswers::where(SocialPollUserAnswers::poll_id,'=',$poll->id)
                ->where(SocialPollUserAnswers::user_id, "=", $user->id)
                ->whereNotIn('id', $selectedPollUserAnswerIDArr)
                ->delete();        
    }

}