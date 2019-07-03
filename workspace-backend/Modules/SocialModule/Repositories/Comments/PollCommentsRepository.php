<?php

namespace Modules\SocialModule\Repositories\Comments;

use Modules\SocialModule\Repositories\PollCommentsRepositoryInterface;
use Modules\SocialModule\Entities\SocialLookup;

use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialPollComment;
use Modules\SocialModule\Entities\SocialPollCommentResponse;
use Modules\SocialModule\Entities\SocialPollResponse;
use Modules\SocialModule\Entities\SocialPollGroup;
use Carbon\Carbon;

class PollCommentsRepository implements PollCommentsRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    /*
     * create, update and delete Poll comment
     */
    public function setPollComment($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $pollComment   = new SocialPollComment();
                $pollComment->{SocialPollComment::slug} = Utilities::getUniqueId();
                $pollComment = $this->pollCommentSetter($pollComment, $request, $user);
                $pollComment->save();

            } else if($request->action == 'update'){
                $pollComment = SocialPollComment::where(SocialPollComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($pollComment)){
                    throw new \Exception("Invalid commentSlug");
                }
               $pollComment = $this->pollCommentSetter($pollComment, $request, $user);
               $pollComment->save();

            } else if($request->action == 'delete'){

                $pollComment = DB::table(SocialPollComment::table)
                        ->where(SocialPollComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($pollComment)){
                    throw new \Exception("Invalid commentSlug");
                }

                DB::table(SocialPollComment::table)
                ->where(SocialPollComment::slug, '=', $request->commentSlug)->delete();

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
        $content['data'] = array("msg"=>"Poll comment ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function pollCommentSetter($pollComment, $request, $user) {
        
        if(empty($request->comment)){
            throw new \Exception("comment cannot be empty!");
        }
        $pollComment->{SocialPollComment::description}  = $request->comment;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $poll = DB::table(SocialPollGroup::table)
                ->where(SocialPollGroup::slug, '=',$request->pollSlug)
                ->first();
        if(empty($poll)){
            throw new \Exception("Invalid pollSlug");
        }
        $pollComment->{SocialPollComment::social_pollgroup_id} = $poll->id;
        $pollComment->{SocialPollComment::user_id}  = $user->id;

        $parentPollCommentObj = DB::table(SocialPollComment::table)
                ->where(SocialPollComment::slug, '=', $request->parentCommentSlug)
                ->where('id', '!=', $pollComment->id)  //prevent self referencing
                ->first();
        if(!empty($request->parentCommentSlug) && empty($parentPollCommentObj)){
            
            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            } else {
                throw new \Exception("Invalid parentCommentSlug");
            }

        } else if(!empty($request->parentCommentSlug) && !empty($parentPollCommentObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            }
            $pollComment->{SocialPollComment::parent_social_comment_id}  = $parentPollCommentObj->id;
        }

        return $pollComment;
    }
    
    public function getPollCommentsAndResponses($request) {

        $user = Auth::user();
        try {
            $pollResponseQueryResultsObj = DB::table(SocialPollResponse::table)
                ->join(SocialPollGroup::table, SocialPollGroup::table . ".id", '=', SocialPollResponse::table . '.' . SocialPollResponse::pollgroup_id)
                ->join(User::table. " AS responseCreator", "responseCreator.id", '=', SocialPollResponse::table . '.' . SocialPollResponse::user_id)
                ->join(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialPollResponse::table . '.' . SocialPollResponse::response_type_id)    
                ->select(

                        SocialPollResponse::table.".".SocialPollResponse::slug. ' AS pollResponseSlug',
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS response',
                        "responseCreator.".User::slug. ' AS pollRespondingUserSlug',
                        "responseCreator.".User::name. ' AS pollRespondingUserName',
                        "responseCreator.".User::email. ' AS pollRespondingUserEmail')
                ->where(SocialPollGroup::table.'.'.SocialPollGroup::slug,'=',$request->pollSlug)
                ->get();


            $pollResponseArr = !empty($pollResponseQueryResultsObj)? $pollResponseQueryResultsObj->toArray():[];

            $pollCommentsQueryResultsObj = DB::table(SocialPollComment::table)
                ->join(SocialPollGroup::table, SocialPollGroup::table . ".id", '=', SocialPollComment::table . '.' . SocialPollComment::social_pollgroup_id)    
                ->join(User::table. " AS pollCreator", "pollCreator.id", '=', SocialPollComment::table . '.' . SocialPollComment::user_id)
                ->join(User::table. " AS commentCreator", "commentCreator.id", '=', SocialPollComment::table . '.' . SocialPollComment::user_id)    
                ->leftJoin(UserProfile::table.' as commentCreatorProfile', "commentCreator.id", '=', 'commentCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(SocialPollCommentResponse::table, SocialPollCommentResponse::table . ".".SocialPollCommentResponse::pollgroup_comment_id, '=', SocialPollComment::table . '.id')
                ->leftJoin(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialPollCommentResponse::table . '.' . SocialPollCommentResponse::response_type_id)
                ->leftJoin(User::table. " AS commentsResponseCreator", "commentsResponseCreator.id", '=', SocialPollCommentResponse::table . '.' . SocialPollCommentResponse::user_id)
                ->leftJoin(SocialPollComment::table. " AS SocialPollCommentParent", "SocialPollCommentParent.id", '=', SocialPollComment::table . '.' . SocialPollComment::parent_social_comment_id)
                ->select(
                        SocialPollGroup::table.".".SocialPollGroup::slug. ' AS pollSlug',
                        "pollCreator.".User::slug. ' AS pollUserSlug',
                        SocialPollComment::table.".".SocialPollComment::slug. ' AS pollCommentSlug',
                        "SocialPollCommentParent.". SocialPollComment::slug. ' AS pollParentCommentSlug',
                        SocialPollComment::table.".".SocialPollComment::description. ' AS comment',
                        "commentCreator.".User::slug. ' AS commentCreatorUserSlug',
                        "commentCreator.".User::name. ' AS commentCreatorUserName',
                        "commentCreator.".User::email. ' AS commentCreatorUserEmail',
                        DB::raw('concat("'. $this->s3BasePath.'", commentCreatorProfile.'. UserProfile::image_path.') as commentCreatorImageUrl'),

                        SocialPollCommentResponse::table.".".SocialPollCommentResponse::slug." AS commentResponseSlug",
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS commentsResponse',
                        "commentsResponseCreator.".User::slug. ' AS commentsRespondingUserSlug',
                        "commentsResponseCreator.".User::name. ' AS commentsRespondingUserName',
                        "commentsResponseCreator.".User::email. ' AS commentsRespondingUserEmail'
                        )
                ->where(SocialPollGroup::table.'.'.SocialPollGroup::slug,'=',$request->pollSlug)
                ->get();

            $aggregatorArr = array();

            $pollCommentsQueryResultsObj->each(function($item) use (&$aggregatorArr, $user){

                $aggregatorArr[$item->pollCommentSlug]["pollCommentSlug"] = $item->pollCommentSlug;
                $aggregatorArr[$item->pollCommentSlug]["pollParentCommentSlug"] = $item->pollParentCommentSlug;
                $aggregatorArr[$item->pollCommentSlug]["comment"] = $item->comment;
                $aggregatorArr[$item->pollCommentSlug]["commentCreatorUserSlug"] = $item->commentCreatorUserSlug;
                $aggregatorArr[$item->pollCommentSlug]["commentCreatorUserName"] = $item->commentCreatorUserName;
                $aggregatorArr[$item->pollCommentSlug]["commentCreatorUserEmail"] = $item->commentCreatorUserEmail;
                $aggregatorArr[$item->pollCommentSlug]["commentCreatorImageUrl"] = $item->commentCreatorImageUrl;
                //set only if $aggregatorArr[$item->pollCommentSlug]["commentedByMe"]  is false or null 
                if( empty($aggregatorArr[$item->pollCommentSlug]["commentedByMe"]) ){
                    $aggregatorArr[$item->pollCommentSlug]["commentedByMe"] = ($user->slug == $item->commentCreatorUserSlug) ? true : false;
                }
                
                if(empty($aggregatorArr[$item->pollCommentSlug]["yourCommentResponse"])){
                    $aggregatorArr[$item->pollCommentSlug]["yourCommentResponse"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentsResponse :"";
                    $aggregatorArr[$item->pollCommentSlug]["yourCommentResponseSlug"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentResponseSlug :null;
                }
                if(!empty($item->commentResponseSlug)){
                    $aggregatorArr[$item->pollCommentSlug]["commentResponses"][] = 
                            array(
                                "commentResponseSlug" => $item->commentResponseSlug,
                                "commentsResponse" => $item->commentsResponse,
                                "commentsRespondingUserSlug" => $item->commentsRespondingUserSlug,
                                "commentsRespondingUserName" => $item->commentsRespondingUserName,
                                "commentsRespondingUserEmail" => $item->commentsRespondingUserEmail
                            );
                } else {
                    $aggregatorArr[$item->pollCommentSlug]["yourCommentResponse"] = "";
                    $aggregatorArr[$item->pollCommentSlug]["yourCommentResponseSlug"] = null;
                    $aggregatorArr[$item->pollCommentSlug]["commentResponses"] = array();
                }
            });
            //dd($aggregatorArr);

            $completeResultsArr = array();
            $completeResultsArr["pollComments"] = array();
            foreach ($aggregatorArr as $key => $value) {
                $completeResultsArr['pollComments'][] = $value;
            }
            $completeResultsArr['pollResponses'] = $pollResponseArr;

        } catch (\Exception $e) {
            $errmsg = $e->getMessage();
            $content = array();
            $content['error']   = array("msg"=>$errmsg);
            $content['code']    =  422;
            $content['status']  = "ERROR";
            return $content;
        }
        $content = array();
        $content['data'] = $completeResultsArr;
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    /*
     * create, update and delete poll comment response
     */
    public function setPollCommentResponse($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){
                $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
                if(empty($responseTypeObj)){
                    throw new \Exception("Invalid response ");
                }
                $pollComment = DB::table(SocialPollComment::table)
                        ->where(SocialPollComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($pollComment)){
                    throw new \Exception("Invalid commentSlug");
                }      
                $previousPollCommentResponse = SocialPollCommentResponse::where(SocialPollCommentResponse::user_id, '=',$user->id)
                        ->where(SocialPollCommentResponse::pollgroup_comment_id, '=',$pollComment->id)
                        ->where(SocialPollCommentResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousPollCommentResponse)){
                    throw new \Exception("Poll comment already ".$request->response."d!");
                }
                $pollCommentResponse   = new SocialPollCommentResponse();
                $pollCommentResponse->{SocialPollCommentResponse::slug} = Utilities::getUniqueId();
                $pollCommentResponse = $this->pollCommentResponseSetter($pollCommentResponse, $request, $user);
                $pollCommentResponse->save();
            } else if($request->action == 'update'){
                $pollCommentResponse = SocialPollCommentResponse::where(SocialPollCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($pollCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }
                $pollCommentResponse = $this->pollCommentResponseSetter($pollCommentResponse, $request, $user);
                $pollCommentResponse->save();
            } else if($request->action == 'delete'){

                $pollCommentResponse = DB::table(SocialPollCommentResponse::table)
                        ->where(SocialPollCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($pollCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }

                DB::table(SocialPollCommentResponse::table)
                ->where(SocialPollCommentResponse::slug, '=', $request->commentResponseSlug)->delete();

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
        $content['data'] = array(
                    "msg"=>"Poll comment response ".$request->action." success",
                    "commentResponseSlug"=>$pollCommentResponse->{SocialPollCommentResponse::slug}
                );
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function pollCommentResponseSetter($pollCommentResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $pollCommentResponse->{SocialPollCommentResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $pollCommentResponse->{SocialPollCommentResponse::org_id}  = $organisationObj->id;
        
        $pollComment = DB::table(SocialPollComment::table)
                ->where(SocialPollComment::slug, '=',$request->commentSlug)
                ->first();
        if(empty($pollComment)){
            throw new \Exception("Invalid commentSlug");
        }
        $pollCommentResponse->{SocialPollCommentResponse::pollgroup_comment_id} = $pollComment->id;
        $pollCommentResponse->{SocialPollCommentResponse::user_id}  = $user->id; 
        return $pollCommentResponse;
    }

    /*
     * create, update and delete poll response (like)
     */
    public function setPollResponse($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
                if(empty($responseTypeObj)){
                    throw new \Exception("Invalid response ");
                }
                $poll = DB::table(SocialPollGroup::table)
                        ->where(SocialPollGroup::slug, '=',$request->pollSlug)
                        ->first();
                if(empty($poll)){
                    throw new \Exception("Invalid pollSlug");
                }                
                $previousPollResponse = SocialPollResponse::where(SocialPollResponse::user_id, '=',$user->id)
                        ->where(SocialPollResponse::pollgroup_id, '=',$poll->id)
                        ->where(SocialPollResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousPollResponse)){
                    throw new \Exception("Poll already ".$request->response."d!");
                }
                
                $pollResponse   = new SocialPollResponse();
                $pollResponse->{SocialPollResponse::slug} = Utilities::getUniqueId();
                $pollResponse = $this->pollResponseSetter($pollResponse, $request, $user);
                $pollResponse->save();
            } else if($request->action == 'update'){
                $pollResponse = SocialPollResponse::where(SocialPollResponse::slug, '=',$request->pollResponseSlug)
                        ->first();
                if(empty($pollResponse)){
                    throw new \Exception("Invalid pollResponseSlug");
                }
                $pollResponse = $this->pollResponseSetter($pollResponse, $request, $user);
                $pollResponse->save();
            } else if($request->action == 'delete'){

                $pollResponse = DB::table(SocialPollResponse::table)
                        ->where(SocialPollResponse::slug, '=',$request->pollResponseSlug)
                        ->first();
                if(empty($pollResponse)){
                    throw new \Exception("Invalid pollResponseSlug");
                }
                DB::table(SocialPollResponse::table)
                ->where(SocialPollResponse::slug, '=', $request->pollResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Poll response ".$request->action." success",
            "pollResponseSlug" => $pollResponse->{SocialPollResponse::slug});
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function pollResponseSetter($pollResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $pollResponse->{SocialPollResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $pollResponse->{SocialPollResponse::org_id}  = $organisationObj->id;
        
        $poll = DB::table(SocialPollGroup::table)
                ->where(SocialPollGroup::slug, '=',$request->pollSlug)
                ->first();
        if(empty($poll)){
            throw new \Exception("Invalid pollSlug");
        }
        $pollResponse->{SocialPollResponse::pollgroup_id} = $poll->id;
        $pollResponse->{SocialPollResponse::user_id}  = $user->id; 
        return $pollResponse;        
    }
    
}