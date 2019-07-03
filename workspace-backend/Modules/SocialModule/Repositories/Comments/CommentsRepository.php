<?php

namespace Modules\SocialModule\Repositories\Comments;

use Modules\SocialModule\Repositories\CommentsRepositoryInterface;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\SocialModule\Entities\SocialAnnouncementComment;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\SocialModule\Entities\SocialAnnouncementResponse;
use Modules\SocialModule\Entities\SocialAnnouncementCommentResponse;
use Modules\Common\Utilities\Utilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialActivityStreamTaskMap;
use Modules\SocialModule\Entities\SocialActivityStreamFormMap;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialMessageComment;
use Modules\SocialModule\Entities\SocialMessageCommentResponse;
use Modules\SocialModule\Entities\SocialMessageResponse;
use Modules\SocialModule\Entities\SocialMessage;
use Carbon\Carbon;

class CommentsRepository implements CommentsRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    /*
     * create, update and delete message comment
     */
    public function setMessageComment($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $messageComment   = new SocialMessageComment();
                $messageComment->{SocialMessageComment::slug} = Utilities::getUniqueId();
                $messageComment = $this->messageCommentSetter($messageComment, $request, $user);
                $messageComment->save();

            } else if($request->action == 'update'){
                $messageComment = SocialMessageComment::where(SocialMessageComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($messageComment)){
                    throw new \Exception("Invalid commentSlug");
                }
               $messageComment = $this->messageCommentSetter($messageComment, $request, $user);
               $messageComment->save();

            } else if($request->action == 'delete'){

                $messageComment = DB::table(SocialMessageComment::table)
                        ->where(SocialMessageComment::slug, '=', $request->commentSlug)
                        ->first();
                if(empty($messageComment)){
                    throw new \Exception("Invalid commentSlug");
                }

                DB::table(SocialMessageComment::table)
                ->where(SocialMessageComment::slug, '=', $request->commentSlug)->delete();

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
        $content['data'] = array("msg"=>"Message comment ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function messageCommentSetter($messageComment, $request, $user) {
        
        if(empty($request->comment)){
            throw new \Exception("comment cannot be empty!");
        }
        $messageComment->{SocialMessageComment::description}  = $request->comment;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $message = DB::table(SocialMessage::table)
                ->where(SocialMessage::slug, '=',$request->msgSlug)
                ->first();
        if(empty($message)){
            throw new \Exception("Invalid messageSlug");
        }
        $messageComment->{SocialMessageComment::social_message_id} = $message->id;
        $messageComment->{SocialMessageComment::user_id}  = $user->id;

        $parentMessageObj = DB::table(SocialMessageComment::table)
                ->where(SocialMessageComment::slug, '=', $request->parentCommentSlug)
                ->where('id', '!=', $messageComment->id) //prevent self referencing
                ->first();
        if(!empty($request->parentCommentSlug) && empty($parentMessageObj)){
            
            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            } else {
                throw new \Exception("Invalid parentCommentSlug");
            }

        } else if(!empty($request->parentCommentSlug) && !empty($parentMessageObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            }

            $messageComment->{SocialMessageComment::parent_social_comment_id}  = $parentMessageObj->id;
        }
        return $messageComment;
    }
    
    public function getMessageCommentsAndResponses($request) {

        $user = Auth::user();
        try {
            $messageResponseQueryResultsObj = DB::table(SocialMessageResponse::table)
                ->join(SocialMessage::table, SocialMessage::table . ".id", '=', SocialMessageResponse::table . '.' . SocialMessageResponse::message_id)
                ->join(User::table. " AS responseCreator", "responseCreator.id", '=', SocialMessageResponse::table . '.' . SocialMessageResponse::user_id)
                ->join(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialMessageResponse::table . '.' . SocialMessageResponse::response_type_id)    
                ->select(
                        //SocialMessage::table.".".SocialMessage::slug. ' AS msgSlug',
                        SocialMessageResponse::table.".".SocialMessageResponse::slug. ' AS messageResponseSlug',
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS response',
                        "responseCreator.".User::slug. ' AS msgRespondingUserSlug',
                        "responseCreator.".User::name. ' AS msgRespondingUserName',
                        "responseCreator.".User::email. ' AS msgRespondingUserEmail')
                ->where(SocialMessage::table.'.'.SocialMessage::slug,'=',$request->msgSlug)
                ->get();
            //dd($messageResponseQueryResultsObj);

            $messageResponseArr = !empty($messageResponseQueryResultsObj)? $messageResponseQueryResultsObj->toArray():[];

            $messageCommentsQueryResultsObj = DB::table(SocialMessageComment::table)
                ->join(SocialMessage::table, SocialMessage::table . ".id", '=', SocialMessageComment::table . '.' . SocialMessageComment::social_message_id)    
                ->join(User::table. " AS msgCreator", "msgCreator.id", '=', SocialMessageComment::table . '.' . SocialMessageComment::user_id)
                ->join(User::table. " AS commentCreator", "commentCreator.id", '=', SocialMessageComment::table . '.' . SocialMessageComment::user_id)
                ->leftJoin(UserProfile::table.' as commentCreatorProfile', "commentCreator.id", '=', 'commentCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(SocialMessageCommentResponse::table, SocialMessageCommentResponse::table . ".".SocialMessageCommentResponse::message_comment_id, '=', SocialMessageComment::table . '.id')
                ->leftJoin(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialMessageCommentResponse::table . '.' . SocialMessageCommentResponse::response_type_id)
                ->leftJoin(User::table. " AS commentsResponseCreator", "commentsResponseCreator.id", '=', SocialMessageCommentResponse::table . '.' . SocialMessageCommentResponse::user_id)
                ->leftJoin(SocialMessageComment::table. " AS SocialMessageCommentParent", "SocialMessageCommentParent.id", '=', SocialMessageComment::table . '.' . SocialMessageComment::parent_social_comment_id)
                ->select(
                        SocialMessage::table.".".SocialMessage::slug. ' AS msgSlug',
                        "msgCreator.".User::slug. ' AS msgUserSlug',
                        SocialMessageComment::table.".".SocialMessageComment::slug. ' AS msgCommentSlug',
                        "SocialMessageCommentParent.". SocialMessageComment::slug. ' AS msgParentCommentSlug',
                        SocialMessageComment::table.".".SocialMessageComment::description. ' AS comment',
                        "commentCreator.".User::slug. ' AS commentCreatorUserSlug',
                        "commentCreator.".User::name. ' AS commentCreatorUserName',
                        "commentCreator.".User::email. ' AS commentCreatorUserEmail',
                        DB::raw('concat("'. $this->s3BasePath.'", commentCreatorProfile.'. UserProfile::image_path.') as commentCreatorImageUrl'),

                        SocialMessageCommentResponse::table.".".SocialMessageCommentResponse::slug." AS commentResponseSlug",
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS commentsResponse',
                        "commentsResponseCreator.".User::slug. ' AS commentsRespondingUserSlug',
                        "commentsResponseCreator.".User::name. ' AS commentsRespondingUserName',
                        "commentsResponseCreator.".User::email. ' AS commentsRespondingUserEmail'
                        )
                ->where(SocialMessage::table.'.'.SocialMessage::slug,'=',$request->msgSlug)
                ->get();
            //dd($messageCommentsQueryResultsObj);

            $aggregatorArr = array();

            $messageCommentsQueryResultsObj->each(function($item) use (&$aggregatorArr,$user){

                $aggregatorArr[$item->msgCommentSlug]["msgCommentSlug"] = $item->msgCommentSlug;
                $aggregatorArr[$item->msgCommentSlug]["msgParentCommentSlug"] = $item->msgParentCommentSlug;
                $aggregatorArr[$item->msgCommentSlug]["comment"] = $item->comment;
                $aggregatorArr[$item->msgCommentSlug]["commentCreatorUserSlug"] = $item->commentCreatorUserSlug;
                $aggregatorArr[$item->msgCommentSlug]["commentCreatorUserName"] = $item->commentCreatorUserName;
                $aggregatorArr[$item->msgCommentSlug]["commentCreatorUserEmail"] = $item->commentCreatorUserEmail;
                $aggregatorArr[$item->msgCommentSlug]["commentCreatorImageUrl"] = $item->commentCreatorImageUrl;

                //set only if $aggregatorArr[$item->msgCommentSlug]["commentedByMe"]  is false or null 
                if( empty($aggregatorArr[$item->msgCommentSlug]["commentedByMe"]) ){
                    $aggregatorArr[$item->msgCommentSlug]["commentedByMe"] = ($user->slug == $item->commentCreatorUserSlug) ? true : false;
                }

                if(!empty($item->commentResponseSlug)){

                    if(empty($aggregatorArr[$item->msgCommentSlug]["yourCommentResponse"])){
                        $aggregatorArr[$item->msgCommentSlug]["yourCommentResponse"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentsResponse :"";
                        $aggregatorArr[$item->msgCommentSlug]["yourCommentResponseSlug"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentResponseSlug :null;
                    }
                    $aggregatorArr[$item->msgCommentSlug]["commentResponses"][] = 
                            array(
                                "commentResponseSlug" => $item->commentResponseSlug,
                                "commentsResponse" => $item->commentsResponse,
                                "commentsRespondingUserSlug" => $item->commentsRespondingUserSlug,
                                "commentsRespondingUserName" => $item->commentsRespondingUserName,
                                "commentsRespondingUserEmail" => $item->commentsRespondingUserEmail
                            );
                } else {
                    $aggregatorArr[$item->msgCommentSlug]["yourCommentResponse"] = "";
                    $aggregatorArr[$item->msgCommentSlug]["yourCommentResponseSlug"] = null;
                    $aggregatorArr[$item->msgCommentSlug]["commentResponses"] = array();
                    
                }
            });
            //dd($aggregatorArr);

            $completeResultsArr = array();
            $completeResultsArr["messageComments"] = array();
            foreach ($aggregatorArr as $key => $value) {
                $completeResultsArr['messageComments'][] = $value;
            }
            $completeResultsArr['messageResponses'] = $messageResponseArr;

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
     * create, update and delete message comment response
     */
    public function setMessageCommentResponse($request)
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
                $messageComment = DB::table(SocialMessageComment::table)
                        ->where(SocialMessageComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($messageComment)){
                    throw new \Exception("Invalid commentSlug");
                }      
                $previousMessageCommentResponse = SocialMessageCommentResponse::where(SocialMessageCommentResponse::user_id, '=',$user->id)
                        ->where(SocialMessageCommentResponse::message_comment_id, '=',$messageComment->id)
                        ->where(SocialMessageCommentResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousMessageCommentResponse)){
                    throw new \Exception("Message comment already ".$request->response."d!");
                }
                $messageCommentResponse   = new SocialMessageCommentResponse();
                $messageCommentResponse->{SocialMessageCommentResponse::slug} = Utilities::getUniqueId();
                $messageCommentResponse = $this->messageCommentResponseSetter($messageCommentResponse, $request, $user);
                $messageCommentResponse->save();
            } else if($request->action == 'update'){
                $messageCommentResponse = SocialMessageCommentResponse::where(SocialMessageCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($messageCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }
                $messageCommentResponse = $this->messageCommentResponseSetter($messageCommentResponse, $request, $user);
                $messageCommentResponse->save();
            } else if($request->action == 'delete'){

                $messageComment = DB::table(SocialMessageCommentResponse::table)
                        ->where(SocialMessageCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($messageComment)){
                    throw new \Exception("Invalid commentResponseSlug");
                }

                DB::table(SocialMessageCommentResponse::table)
                ->where(SocialMessageCommentResponse::slug, '=', $request->commentResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Message comment response ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function messageCommentResponseSetter($messageCommentResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $messageCommentResponse->{SocialMessageCommentResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $messageCommentResponse->{SocialMessageCommentResponse::org_id}  = $organisationObj->id;

        $messageComment = DB::table(SocialMessageComment::table)
                ->where(SocialMessageComment::slug, '=',$request->commentSlug)
                ->first();
        if(empty($messageComment)){
            throw new \Exception("Invalid commentSlug");
        }
        $messageCommentResponse->{SocialMessageCommentResponse::message_comment_id} = $messageComment->id;
        $messageCommentResponse->{SocialMessageCommentResponse::user_id}  = $user->id; 
        return $messageCommentResponse;
    }

    /*
     * create, update and delete message response (like)
     */
    public function setMessageResponse($request)
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
                $message = DB::table(SocialMessage::table)
                        ->where(SocialMessage::slug, '=',$request->msgSlug)
                        ->first();
                if(empty($message)){
                    throw new \Exception("Invalid msgSlug");
                }                
                $previousMessageResponse = SocialMessageResponse::where(SocialMessageResponse::user_id, '=',$user->id)
                        ->where(SocialMessageResponse::message_id, '=',$message->id)
                        ->where(SocialMessageResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousMessageResponse)){
                    throw new \Exception("Message already ".$request->response."d!");
                }
                
                $messageResponse   = new SocialMessageResponse();
                $messageResponse->{SocialMessageResponse::slug} = Utilities::getUniqueId();
                $messageResponse = $this->messageResponseSetter($messageResponse, $request, $user);
                $messageResponse->save();
            } else if($request->action == 'update'){
                $messageResponse = SocialMessageResponse::where(SocialMessageResponse::slug, '=',$request->messageResponseSlug)
                        ->first();
                if(empty($messageResponse)){
                    throw new \Exception("Invalid messageResponseSlug");
                }
                $messageResponse = $this->messageResponseSetter($messageResponse, $request, $user);
                $messageResponse->save();
            } else if($request->action == 'delete'){

                $messageResponse = DB::table(SocialMessageResponse::table)
                        ->where(SocialMessageResponse::slug, '=',$request->messageResponseSlug)
                        ->first();
                if(empty($messageResponse)){
                    throw new \Exception("Invalid messageResponseSlug");
                }
                DB::table(SocialMessageResponse::table)
                ->where(SocialMessageResponse::slug, '=', $request->messageResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Message response ".$request->action." success",
            "messageResponseSlug" => $messageResponse->{SocialMessageResponse::slug});
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function messageResponseSetter($messageResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $messageResponse->{SocialMessageResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $messageResponse->{SocialMessageResponse::org_id}  = $organisationObj->id;
        
        $message = DB::table(SocialMessage::table)
                ->where(SocialMessage::slug, '=',$request->msgSlug)
                ->first();
        if(empty($message)){
            throw new \Exception("Invalid msgSlug");
        }
        $messageResponse->{SocialMessageResponse::message_id} = $message->id;
        $messageResponse->{SocialMessageResponse::user_id}  = $user->id; 
        return $messageResponse;        
    }

//---------------Announcement--------------------------    

    /*
     * create, update and delete Announcement comment
     */
    public function setAnnouncementComment($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $ancComment   = new SocialAnnouncementComment();
                $ancComment->{SocialAnnouncementComment::slug} = Utilities::getUniqueId();
                $ancComment = $this->announcementCommentSetter($ancComment, $request, $user);
                $ancComment->save();

            } else if($request->action == 'update'){
                $ancComment = SocialAnnouncementComment::where(SocialAnnouncementComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($ancComment)){
                    throw new \Exception("Invalid ancSlug");
                }
                $ancComment = $this->announcementCommentSetter($ancComment, $request, $user);
                $ancComment->save();

            } else if($request->action == 'delete'){

                $ancComment = DB::table(SocialAnnouncementComment::table)
                        ->where(SocialAnnouncementComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($ancComment)){
                    throw new \Exception("Invalid ancSlug");
                }
                DB::table(SocialAnnouncementComment::table)
                ->where(SocialAnnouncementComment::slug, '=', $request->commentSlug)->delete();
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
        $content['data'] = array("msg"=>"Announcement comment ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function announcementCommentSetter($ancComment, $request, $user) {
        if(empty($request->comment)){
            throw new \Exception("comment cannot be empty!");
        }
        $ancComment->{SocialAnnouncementComment::description}  = $request->comment;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }

        $announcement = DB::table(SocialAnnouncement::table)
                ->where(SocialAnnouncement::slug, '=',$request->ancSlug)
                ->first();
        if(empty($announcement)){
            throw new \Exception("Invalid ancSlug");
        }
        $ancComment->{SocialAnnouncementComment::social_announcement_id} = $announcement->id;
        $ancComment->{SocialAnnouncementComment::user_id}  = $user->id;

        $parentAncCommentObj = DB::table(SocialAnnouncementComment::table)
                ->where(SocialAnnouncementComment::slug, '=', $request->parentCommentSlug)
                ->where('id', '!=', $ancComment->id) //prevent self referencing
                ->first();

        if(!empty($request->parentCommentSlug) && empty($parentAncCommentObj)){
            
            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            } else {
                throw new \Exception("Invalid parentCommentSlug");
            }
        } else if(!empty($request->parentCommentSlug) && !empty($parentAncCommentObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            }

            $ancComment->{SocialAnnouncementComment::parent_announcement_comment_id}  = $parentAncCommentObj->id;
        }

        return $ancComment;
    }
    
    /*
     * create, update and delete Announcement response (like)
     */
    public function setAnnouncementResponse($request)
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
                $announcement = DB::table(SocialAnnouncement::table)
                        ->where(SocialAnnouncement::slug, '=',$request->ancSlug)
                        ->first();
                if(empty($announcement)){
                    throw new \Exception("Invalid ancSlug");
                }                
                $previousAnnouncementResponse = SocialAnnouncementResponse::where(SocialAnnouncementResponse::user_id, '=',$user->id)
                        ->where(SocialAnnouncementResponse::annoucement_id, '=',$announcement->id)
                        ->where(SocialAnnouncementResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousAnnouncementResponse)){
                    throw new \Exception("Announcement already ".$request->response."d!");
                }
                
                $announcementResponse   = new SocialAnnouncementResponse();
                $announcementResponse->{SocialAnnouncementResponse::slug} = Utilities::getUniqueId();
                $announcementResponse = $this->announcementResponseSetter($announcementResponse, $request, $user);
                $announcementResponse->save();
            } else if($request->action == 'update'){
                $announcementResponse = SocialAnnouncementResponse::where(SocialAnnouncementResponse::slug, '=',$request->ancResponseSlug)
                        ->first();
                if(empty($announcementResponse)){
                    throw new \Exception("Invalid ancResponseSlug");
                }
                $announcementResponse = $this->announcementResponseSetter($announcementResponse, $request, $user);
                $announcementResponse->save();
            } else if($request->action == 'delete'){

                $announcementResponse = DB::table(SocialAnnouncementResponse::table)
                        ->where(SocialAnnouncementResponse::slug, '=',$request->ancResponseSlug)
                        ->first();
                if(empty($announcementResponse)){
                    throw new \Exception("Invalid ancResponseSlug");
                }
                DB::table(SocialAnnouncementResponse::table)
                ->where(SocialAnnouncementResponse::slug, '=', $request->ancResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Announcement response ".$request->action." success",
            "ancResponseSlug" => $announcementResponse->{SocialAnnouncementResponse::slug});
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function announcementResponseSetter($announcementResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $announcementResponse->{SocialAnnouncementResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $announcementResponse->{SocialAnnouncementResponse::org_id}  = $organisationObj->id;
        
        $announcement = DB::table(SocialAnnouncement::table)
                ->where(SocialAnnouncement::slug, '=',$request->ancSlug)
                ->first();
        if(empty($announcement)){
            throw new \Exception("Invalid ancSlug");
        }
        $announcementResponse->{SocialAnnouncementResponse::annoucement_id} = $announcement->id;
        $announcementResponse->{SocialAnnouncementResponse::user_id}  = $user->id; 
        return $announcementResponse;        
    }

    
    /*
     * create, update and delete Announcement comment response
     */
    public function setAnnouncementCommentResponse($request)
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
                $ancComment = DB::table(SocialAnnouncementComment::table)
                        ->where(SocialAnnouncementComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($ancComment)){
                    throw new \Exception("Invalid commentSlug");
                }
                $previousAncCommentResponse = SocialAnnouncementCommentResponse::where(SocialAnnouncementCommentResponse::user_id, '=',$user->id)
                        ->where(SocialAnnouncementCommentResponse::anc_comment_id, '=',$ancComment->id)
                        ->where(SocialAnnouncementCommentResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousAncCommentResponse)){
                    throw new \Exception("Announcement comment already ".$request->response."d!");
                }
                $ancCommentResponse   = new SocialAnnouncementCommentResponse();
                $ancCommentResponse->{SocialAnnouncementCommentResponse::slug} = Utilities::getUniqueId();
                $ancCommentResponse = $this->announcementCommentResponseSetter($ancCommentResponse, $request, $user);
                $ancCommentResponse->save();
            } else if($request->action == 'update'){
                $ancCommentResponse = SocialAnnouncementCommentResponse::where(SocialAnnouncementCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($ancCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }
                $ancCommentResponse = $this->announcementCommentResponseSetter($ancCommentResponse, $request, $user);
                $ancCommentResponse->save();
            } else if($request->action == 'delete'){

                $ancCommentResp = DB::table(SocialAnnouncementCommentResponse::table)
                        ->where(SocialAnnouncementCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($ancCommentResp)){
                    throw new \Exception("Invalid commentResponseSlug");
                }

                DB::table(SocialAnnouncementCommentResponse::table)
                ->where(SocialAnnouncementCommentResponse::slug, '=', $request->commentResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Announcement comment response ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }    
    
    private function announcementCommentResponseSetter($ancCommentResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $ancCommentResponse->{SocialAnnouncementCommentResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $ancCommentResponse->{SocialAnnouncementCommentResponse::org_id}  = $organisationObj->id;
        
        $messageComment = DB::table(SocialAnnouncementComment::table)
                ->where(SocialAnnouncementComment::slug, '=',$request->commentSlug)
                ->first();
        if(empty($messageComment)){
            throw new \Exception("Invalid commentSlug");
        }
        $ancCommentResponse->{SocialAnnouncementCommentResponse::anc_comment_id} = $messageComment->id;
        $ancCommentResponse->{SocialAnnouncementCommentResponse::user_id}  = $user->id; 
        return $ancCommentResponse;
    }    
    
    public function getAnnouncementCommentsAndResponses($request) {

        $user = Auth::user();
        try {
            $announcementResponseQueryResultsObj = DB::table(SocialAnnouncementResponse::table)
                ->join(SocialAnnouncement::table, SocialAnnouncement::table . ".id", '=', SocialAnnouncementResponse::table . '.' . SocialAnnouncementResponse::annoucement_id)
                ->join(User::table. " AS responseCreator", "responseCreator.id", '=', SocialAnnouncementResponse::table . '.' . SocialAnnouncementResponse::user_id)
                ->join(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialAnnouncementResponse::table . '.' . SocialAnnouncementResponse::response_type_id)    
                ->select(
                        //SocialAnnouncement::table.".".SocialAnnouncement::slug. ' AS ancSlug',
                        SocialAnnouncementResponse::table.".".SocialAnnouncementResponse::slug. ' AS ancResponseSlug',
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS response',
                        "responseCreator.".User::slug. ' AS ancRespondingUserSlug',
                        "responseCreator.".User::name. ' AS ancRespondingUserName',
                        "responseCreator.".User::email. ' AS ancRespondingUserEmail')
                ->where(SocialAnnouncement::table.'.'.SocialAnnouncement::slug,'=',$request->ancSlug)
                ->get();
            //dd($announcementResponseQueryResultsObj);

            $announcementResponseArr = !empty($announcementResponseQueryResultsObj)? $announcementResponseQueryResultsObj->toArray():[];

            $announcementCommentsQueryResultsObj = DB::table(SocialAnnouncementComment::table)
                ->join(SocialAnnouncement::table, SocialAnnouncement::table . ".id", '=', SocialAnnouncementComment::table . '.' . SocialAnnouncementComment::social_announcement_id)    
                ->join(User::table. " AS ancCreator", "ancCreator.id", '=', SocialAnnouncementComment::table . '.' . SocialAnnouncementComment::user_id)
                ->join(User::table. " AS commentCreator", "commentCreator.id", '=', SocialAnnouncementComment::table . '.' . SocialAnnouncementComment::user_id)
                ->leftJoin(UserProfile::table.' as commentCreatorProfile', "commentCreator.id", '=', 'commentCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(SocialAnnouncementCommentResponse::table, SocialAnnouncementCommentResponse::table . ".".SocialAnnouncementCommentResponse::anc_comment_id, '=', SocialAnnouncementComment::table . '.id')
                ->leftJoin(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialAnnouncementCommentResponse::table . '.' . SocialAnnouncementCommentResponse::response_type_id)
                ->leftJoin(User::table. " AS commentsResponseCreator", "commentsResponseCreator.id", '=', SocialAnnouncementCommentResponse::table . '.' . SocialAnnouncementCommentResponse::user_id)
                ->leftJoin(SocialAnnouncementComment::table. " AS SocialAncCommentParent", "SocialAncCommentParent.id", '=', SocialAnnouncementComment::table . '.' . SocialAnnouncementComment::parent_announcement_comment_id)
                ->select(
                        SocialAnnouncement::table.".".SocialAnnouncement::slug. ' AS ancSlug',
                        "ancCreator.".User::slug. ' AS ancUserSlug',
                        SocialAnnouncementComment::table.".".SocialAnnouncementComment::slug. ' AS ancCommentSlug',
                        "SocialAncCommentParent.". SocialAnnouncementComment::slug. ' AS ancParentCommentSlug',
                        SocialAnnouncementComment::table.".".SocialAnnouncementComment::description. ' AS comment',
                        "commentCreator.".User::slug. ' AS commentCreatorUserSlug',
                        "commentCreator.".User::name. ' AS commentCreatorUserName',
                        "commentCreator.".User::email. ' AS commentCreatorUserEmail',
                        DB::raw('concat("'. $this->s3BasePath.'", commentCreatorProfile.'. UserProfile::image_path.') as commentCreatorImageUrl'),
                        SocialAnnouncementCommentResponse::table.".".SocialAnnouncementCommentResponse::slug." AS commentResponseSlug",
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS commentsResponse',
                        "commentsResponseCreator.".User::slug. ' AS commentsRespondingUserSlug',
                        "commentsResponseCreator.".User::name. ' AS commentsRespondingUserName',
                        "commentsResponseCreator.".User::email. ' AS commentsRespondingUserEmail'
                        )
                ->where(SocialAnnouncement::table.'.'.SocialAnnouncement::slug,'=',$request->ancSlug)
                ->get();

            $aggregatorArr = array();

            $announcementCommentsQueryResultsObj->each(function($item) use (&$aggregatorArr, $user){

                $aggregatorArr[$item->ancCommentSlug]["ancCommentSlug"] = $item->ancCommentSlug;
                $aggregatorArr[$item->ancCommentSlug]["ancParentCommentSlug"] = $item->ancParentCommentSlug;
                $aggregatorArr[$item->ancCommentSlug]["comment"] = $item->comment;
                $aggregatorArr[$item->ancCommentSlug]["commentCreatorUserSlug"] = $item->commentCreatorUserSlug;
                $aggregatorArr[$item->ancCommentSlug]["commentCreatorUserName"] = $item->commentCreatorUserName;
                $aggregatorArr[$item->ancCommentSlug]["commentCreatorUserEmail"] = $item->commentCreatorUserEmail;
                $aggregatorArr[$item->ancCommentSlug]["commentCreatorImageUrl"] = $item->commentCreatorImageUrl;
                
                //set only if $aggregatorArr[$item->ancCommentSlug]["commentedByMe"]  is false or null 
                if( empty($aggregatorArr[$item->ancCommentSlug]["commentedByMe"]) ){
                    $aggregatorArr[$item->ancCommentSlug]["commentedByMe"] = ($user->slug == $item->commentCreatorUserSlug) ? true : false;
                }

                if(empty($aggregatorArr[$item->ancCommentSlug]["yourCommentResponse"])){
                    $aggregatorArr[$item->ancCommentSlug]["yourCommentResponse"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentsResponse :"";
                    $aggregatorArr[$item->ancCommentSlug]["yourCommentResponseSlug"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentResponseSlug :null;
                }
                if(!empty($item->commentResponseSlug)){
                    $aggregatorArr[$item->ancCommentSlug]["commentResponses"][] = 
                            array(
                                "commentResponseSlug" => $item->commentResponseSlug,
                                "commentsResponse" => $item->commentsResponse,
                                "commentsRespondingUserSlug" => $item->commentsRespondingUserSlug,
                                "commentsRespondingUserName" => $item->commentsRespondingUserName,
                                "commentsRespondingUserEmail" => $item->commentsRespondingUserEmail
                            );
                } else {
                    $aggregatorArr[$item->ancCommentSlug]["yourCommentResponse"] = "";
                    $aggregatorArr[$item->ancCommentSlug]["yourCommentResponseSlug"] = null;
                    $aggregatorArr[$item->ancCommentSlug]["commentResponses"] = array();
                }
            });
            //dd($aggregatorArr);

            $completeResultsArr = array();
            $completeResultsArr["announcementComments"] = array();
            foreach ($aggregatorArr as $key => $value) {
                $completeResultsArr['announcementComments'][] = $value;
            }
            $completeResultsArr['announcementResponses'] = $announcementResponseArr;

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
}