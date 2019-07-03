<?php

namespace Modules\SocialModule\Repositories\Comments;

use Modules\SocialModule\Repositories\EventCommentsRepositoryInterface;
use Modules\SocialModule\Entities\SocialLookup;

use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialEventComment;
use Modules\SocialModule\Entities\SocialEventCommentResponse;
use Modules\SocialModule\Entities\SocialEventResponse;
use Modules\SocialModule\Entities\SocialEvent;
use Carbon\Carbon;

class EventCommentsRepository implements EventCommentsRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    /*
     * create, update and delete event comment
     */
    public function setEventComment($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $eventComment   = new SocialEventComment();
                $eventComment->{SocialEventComment::slug} = Utilities::getUniqueId();
                $eventComment = $this->eventCommentSetter($eventComment, $request, $user);
                $eventComment->save();

            } else if($request->action == 'update'){
                $eventComment = SocialEventComment::where(SocialEventComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($eventComment)){
                    throw new \Exception("Invalid commentSlug");
                }
               $eventComment = $this->eventCommentSetter($eventComment, $request, $user);
               $eventComment->save();

            } else if($request->action == 'delete'){

                $eventComment = DB::table(SocialEventComment::table)
                        ->where(SocialEventComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($eventComment)){
                    throw new \Exception("Invalid commentSlug");
                }

                DB::table(SocialEventComment::table)
                ->where(SocialEventComment::slug, '=', $request->commentSlug)->delete();

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
        $content['data'] = array("msg"=>"Event comment ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function eventCommentSetter($eventComment, $request, $user) {

        if(empty($request->comment)){
            throw new \Exception("comment cannot be empty!");
        }
        $eventComment->{SocialEventComment::description}  = $request->comment;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $event = DB::table(SocialEvent::table)
                ->where(SocialEvent::event_slug, '=',$request->eventSlug)
                ->first();
        if(empty($event)){
            throw new \Exception("Invalid eventSlug");
        }
        $eventComment->{SocialEventComment::social_event_id} = $event->id;
        $eventComment->{SocialEventComment::user_id}  = $user->id;

        $parentEventCommentObj = DB::table(SocialEventComment::table)
                ->where(SocialEventComment::slug, '=', $request->parentCommentSlug)
                ->where('id', '!=', $eventComment->id) //prevent self referencing
                ->first();
        if(!empty($request->parentCommentSlug) && empty($parentEventCommentObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            } else {
                throw new \Exception("Invalid parentCommentSlug");
            }

        } else if(!empty($request->parentCommentSlug) && !empty($parentEventCommentObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            }

            $eventComment->{SocialEventComment::parent_event_comment_id}  = $parentEventCommentObj->id;
        }

        return $eventComment;
    }
    
    public function getEventCommentsAndResponses($request) {

        $user = Auth::user();
        try {
            $eventResponseQueryResultsObj = DB::table(SocialEventResponse::table)
                ->join(SocialEvent::table, SocialEvent::table . ".id", '=', SocialEventResponse::table . '.' . SocialEventResponse::event_id)
                ->join(User::table. " AS responseCreator", "responseCreator.id", '=', SocialEventResponse::table . '.' . SocialEventResponse::user_id)
                ->join(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialEventResponse::table . '.' . SocialEventResponse::response_type_id)    
                ->select(

                        SocialEventResponse::table.".".SocialEventResponse::slug. ' AS eventResponseSlug',
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS response',
                        "responseCreator.".User::slug. ' AS eventRespondingUserSlug',
                        "responseCreator.".User::name. ' AS eventRespondingUserName',
                        "responseCreator.".User::email. ' AS eventRespondingUserEmail')
                ->where(SocialEvent::table.'.'.SocialEvent::event_slug,'=',$request->eventSlug)
                ->get();
            //dd($eventResponseQueryResultsObj);

            $eventResponseArr = !empty($eventResponseQueryResultsObj)? $eventResponseQueryResultsObj->toArray():[];

            $eventCommentsQueryResultsObj = DB::table(SocialEventComment::table)
                ->join(SocialEvent::table, SocialEvent::table . ".id", '=', SocialEventComment::table . '.' . SocialEventComment::social_event_id)    
                ->join(User::table. " AS eventCreator", "eventCreator.id", '=', SocialEventComment::table . '.' . SocialEventComment::user_id)
                ->join(User::table. " AS commentCreator", "commentCreator.id", '=', SocialEventComment::table . '.' . SocialEventComment::user_id)    
                ->leftJoin(UserProfile::table.' as commentCreatorProfile', "commentCreator.id", '=', 'commentCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(SocialEventCommentResponse::table, SocialEventCommentResponse::table . ".".SocialEventCommentResponse::event_comment_id, '=', SocialEventComment::table . '.id')
                ->leftJoin(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialEventCommentResponse::table . '.' . SocialEventCommentResponse::response_type_id)
                ->leftJoin(User::table. " AS commentsResponseCreator", "commentsResponseCreator.id", '=', SocialEventCommentResponse::table . '.' . SocialEventCommentResponse::user_id)
                ->leftJoin(SocialEventComment::table. " AS SocialEventCommentParent", "SocialEventCommentParent.id", '=', SocialEventComment::table . '.' . SocialEventComment::parent_event_comment_id)
                ->select(
                        SocialEvent::table.".".SocialEvent::event_slug. ' AS eventSlug',
                        "eventCreator.".User::slug. ' AS eventUserSlug',
                        SocialEventComment::table.".".SocialEventComment::slug. ' AS eventCommentSlug',
                        "SocialEventCommentParent.". SocialEventComment::slug. ' AS eventParentCommentSlug',
                        SocialEventComment::table.".".SocialEventComment::description. ' AS comment',
                        "commentCreator.".User::slug. ' AS commentCreatorUserSlug',
                        "commentCreator.".User::name. ' AS commentCreatorUserName',
                        "commentCreator.".User::email. ' AS commentCreatorUserEmail',
                        DB::raw('concat("'. $this->s3BasePath.'", commentCreatorProfile.'. UserProfile::image_path.') as commentCreatorImageUrl'),


                        SocialEventCommentResponse::table.".".SocialEventCommentResponse::slug." AS commentResponseSlug",
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS commentsResponse',
                        "commentsResponseCreator.".User::slug. ' AS commentsRespondingUserSlug',
                        "commentsResponseCreator.".User::name. ' AS commentsRespondingUserName',
                        "commentsResponseCreator.".User::email. ' AS commentsRespondingUserEmail'
                        )
                ->where(SocialEvent::table.'.'.SocialEvent::event_slug,'=',$request->eventSlug)
                ->get();
            //dd($eventCommentsQueryResultsObj);

            $aggregatorArr = array();

            $eventCommentsQueryResultsObj->each(function($item) use (&$aggregatorArr, $user){

                $aggregatorArr[$item->eventCommentSlug]["eventCommentSlug"] = $item->eventCommentSlug;
                $aggregatorArr[$item->eventCommentSlug]["eventParentCommentSlug"] = $item->eventParentCommentSlug;
                $aggregatorArr[$item->eventCommentSlug]["comment"] = $item->comment;
                $aggregatorArr[$item->eventCommentSlug]["commentCreatorUserSlug"] = $item->commentCreatorUserSlug;
                $aggregatorArr[$item->eventCommentSlug]["commentCreatorUserName"] = $item->commentCreatorUserName;
                $aggregatorArr[$item->eventCommentSlug]["commentCreatorUserEmail"] = $item->commentCreatorUserEmail;
                $aggregatorArr[$item->eventCommentSlug]["commentCreatorImageUrl"] = $item->commentCreatorImageUrl;
                //set only if $aggregatorArr[$item->eventCommentSlug]["commentedByMe"]  is false or null 
                if( empty($aggregatorArr[$item->eventCommentSlug]["commentedByMe"]) ){
                    $aggregatorArr[$item->eventCommentSlug]["commentedByMe"] = ($user->slug == $item->commentCreatorUserSlug) ? true : false;
                }
                
                if(empty($aggregatorArr[$item->eventCommentSlug]["yourCommentResponse"])){
                    $aggregatorArr[$item->eventCommentSlug]["yourCommentResponse"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentsResponse :"";
                    $aggregatorArr[$item->eventCommentSlug]["yourCommentResponseSlug"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentResponseSlug :null;
                }

                if(!empty($item->commentResponseSlug)){
                    $aggregatorArr[$item->eventCommentSlug]["commentResponses"][] = 
                            array(
                                "commentResponseSlug" => $item->commentResponseSlug,
                                "commentsResponse" => $item->commentsResponse,
                                "commentsRespondingUserSlug" => $item->commentsRespondingUserSlug,
                                "commentsRespondingUserName" => $item->commentsRespondingUserName,
                                "commentsRespondingUserEmail" => $item->commentsRespondingUserEmail
                            );
                } else {
                    $aggregatorArr[$item->eventCommentSlug]["yourCommentResponse"] = "";
                    $aggregatorArr[$item->eventCommentSlug]["yourCommentResponseSlug"] = null;
                    $aggregatorArr[$item->eventCommentSlug]["commentResponses"] = array();
                }
            });
            //dd($aggregatorArr);

            $completeResultsArr = array();
            $completeResultsArr["eventComments"] = array();
            foreach ($aggregatorArr as $key => $value) {
                $completeResultsArr['eventComments'][] = $value;
            }
            $completeResultsArr['eventResponses'] = $eventResponseArr;

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
        $content['data'] = $completeResultsArr;
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    /*
     * create, update and delete event comment response
     */
    public function setEventCommentResponse($request)
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
                $eventComment = DB::table(SocialEventComment::table)
                        ->where(SocialEventComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($eventComment)){
                    throw new \Exception("Invalid commentSlug");
                }      
                $previousEventCommentResponse = SocialEventCommentResponse::where(SocialEventCommentResponse::user_id, '=',$user->id)
                        ->where(SocialEventCommentResponse::event_comment_id, '=',$eventComment->id)
                        ->where(SocialEventCommentResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousEventCommentResponse)){
                    throw new \Exception("Event comment already ".$request->response."d!");
                }
                $eventCommentResponse   = new SocialEventCommentResponse();
                $eventCommentResponse->{SocialEventCommentResponse::slug} = Utilities::getUniqueId();
                $eventCommentResponse = $this->eventCommentResponseSetter($eventCommentResponse, $request, $user);
                $eventCommentResponse->save();
            } else if($request->action == 'update'){
                $eventCommentResponse = SocialEventCommentResponse::where(SocialEventCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($eventCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }
                $eventCommentResponse = $this->eventCommentResponseSetter($eventCommentResponse, $request, $user);
                $eventCommentResponse->save();
            } else if($request->action == 'delete'){

                $eventCommentResponse = DB::table(SocialEventCommentResponse::table)
                        ->where(SocialEventCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($eventCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }

                DB::table(SocialEventCommentResponse::table)
                ->where(SocialEventCommentResponse::slug, '=', $request->commentResponseSlug)->delete();

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
            "msg"=>"Event comment response ".$request->action." success",
            "commentResponseSlug" => $eventCommentResponse->{SocialEventCommentResponse::slug}
            );
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function eventCommentResponseSetter($eventCommentResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $eventCommentResponse->{SocialEventCommentResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $eventCommentResponse->{SocialEventCommentResponse::org_id}  = $organisationObj->id;
        
        $eventComment = DB::table(SocialEventComment::table)
                ->where(SocialEventComment::slug, '=',$request->commentSlug)
                ->first();
        if(empty($eventComment)){
            throw new \Exception("Invalid commentSlug");
        }
        $eventCommentResponse->{SocialEventCommentResponse::event_comment_id} = $eventComment->id;
        $eventCommentResponse->{SocialEventCommentResponse::user_id}  = $user->id; 
        return $eventCommentResponse;
    }

    /*
     * create, update and delete event response (like)
     */
    public function setEventResponse($request)
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
                $event = DB::table(SocialEvent::table)
                        ->where(SocialEvent::event_slug, '=',$request->eventSlug)
                        ->first();
                if(empty($event)){
                    throw new \Exception("Invalid eventSlug");
                }                
                $previousEventResponse = SocialEventResponse::where(SocialEventResponse::user_id, '=',$user->id)
                        ->where(SocialEventResponse::event_id, '=',$event->id)
                        ->where(SocialEventResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousEventResponse)){
                    throw new \Exception("Event already ".$request->response."d!");
                }
                
                $eventResponse   = new SocialEventResponse();
                $eventResponse->{SocialEventResponse::slug} = Utilities::getUniqueId();
                $eventResponse = $this->eventResponseSetter($eventResponse, $request, $user);
                $eventResponse->save();
            } else if($request->action == 'update'){
                $eventResponse = SocialEventResponse::where(SocialEventResponse::slug, '=',$request->eventResponseSlug)
                        ->first();
                if(empty($eventResponse)){
                    throw new \Exception("Invalid eventResponseSlug");
                }
                $eventResponse = $this->eventResponseSetter($eventResponse, $request, $user);
                $eventResponse->save();
            } else if($request->action == 'delete'){

                $eventResponse = DB::table(SocialEventResponse::table)
                        ->where(SocialEventResponse::slug, '=',$request->eventResponseSlug)
                        ->first();
                if(empty($eventResponse)){
                    throw new \Exception("Invalid eventResponseSlug");
                }
                DB::table(SocialEventResponse::table)
                ->where(SocialEventResponse::slug, '=', $request->eventResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Event response ".$request->action." success",
            "eventResponseSlug" => $eventResponse->{SocialEventResponse::slug});
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function eventResponseSetter($eventResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $eventResponse->{SocialEventResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $eventResponse->{SocialEventResponse::org_id}  = $organisationObj->id;
        
        $event = DB::table(SocialEvent::table)
                ->where(SocialEvent::event_slug, '=',$request->eventSlug)
                ->first();
        if(empty($event)){
            throw new \Exception("Invalid eventSlug");
        }
        $eventResponse->{SocialEventResponse::event_id} = $event->id;
        $eventResponse->{SocialEventResponse::user_id}  = $user->id; 
        return $eventResponse; 
    }
    
}