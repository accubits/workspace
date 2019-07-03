<?php

namespace Modules\SocialModule\Repositories\Comments;

use Modules\SocialModule\Repositories\AppreciationCommentsRepositoryInterface;
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
use Modules\SocialModule\Entities\SocialAppreciationComment;
use Modules\SocialModule\Entities\SocialAppreciationCommentResponse;
use Modules\SocialModule\Entities\SocialAppreciationResponse;
use Modules\SocialModule\Entities\SocialAppreciation;
use Carbon\Carbon;

class AppreciationCommentsRepository implements AppreciationCommentsRepositoryInterface
{

    public $s3BasePath;
    public function __construct()
    {
        $this->s3BasePath= env('S3_PATH');
    }

    /*
     * create, update and delete appreciation comment
     */
    public function setAppreciationComment($request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {

            if($request->action == 'create'){

                $appreciationComment   = new SocialAppreciationComment();
                $appreciationComment->{SocialAppreciationComment::slug} = Utilities::getUniqueId();
                $appreciationComment = $this->appreciationCommentSetter($appreciationComment, $request, $user);
                $appreciationComment->save();

            } else if($request->action == 'update'){
                $appreciationComment = SocialAppreciationComment::where(SocialAppreciationComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($appreciationComment)){
                    throw new \Exception("Invalid commentSlug");
                }
               $appreciationComment = $this->appreciationCommentSetter($appreciationComment, $request, $user);
               $appreciationComment->save();

            } else if($request->action == 'delete'){

                $appreciationComment = DB::table(SocialAppreciationComment::table)
                        ->where(SocialAppreciationComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($appreciationComment)){
                    throw new \Exception("Invalid commentSlug");
                }

                DB::table(SocialAppreciationComment::table)
                ->where(SocialAppreciationComment::slug, '=', $request->commentSlug)->delete();

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
        $content['data'] = array("msg"=>"Appreciation comment ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }

    private function appreciationCommentSetter($appreciationComment, $request, $user) {
        if(empty($request->comment)){
            throw new \Exception("comment cannot be empty!");
        }
        $appreciationComment->{SocialAppreciationComment::description}  = $request->comment;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $appreciation = DB::table(SocialAppreciation::table)
                ->where(SocialAppreciation::slug, '=',$request->aprSlug)
                ->first();
        if(empty($appreciation)){
            throw new \Exception("Invalid appreciationSlug");
        }
        $appreciationComment->{SocialAppreciationComment::social_appreciation_id} = $appreciation->id;
        $appreciationComment->{SocialAppreciationComment::user_id}  = $user->id;

        $parentAppreciationCommentObj = DB::table(SocialAppreciationComment::table)
                ->where(SocialAppreciationComment::slug, '=', $request->parentCommentSlug)
                ->where('id', '!=', $appreciationComment->id)  //prevent self referencing
                ->first();
        if(!empty($request->parentCommentSlug) && empty($parentAppreciationCommentObj)){
            
            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            } else {
                throw new \Exception("Invalid parentCommentSlug");
            }

        } else if(!empty($request->parentCommentSlug) && !empty($parentAppreciationCommentObj)){

            if($request->parentCommentSlug == $request->commentSlug){
                throw new \Exception("Invalid parentCommentSlug, self referencing error "); 
            }
            $appreciationComment->{SocialAppreciationComment::parent_social_comment_id}  = $parentAppreciationCommentObj->id;
        }

        return $appreciationComment;
    }
    
    public function getAppreciationCommentsAndResponses($request) {

        $user = Auth::user();
        try {
            $appreciationResponseQueryResultsObj = DB::table(SocialAppreciationResponse::table)
                ->join(SocialAppreciation::table, SocialAppreciation::table . ".id", '=', SocialAppreciationResponse::table . '.' . SocialAppreciationResponse::appreciation_id)
                ->join(User::table. " AS responseCreator", "responseCreator.id", '=', SocialAppreciationResponse::table . '.' . SocialAppreciationResponse::user_id)
                ->join(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialAppreciationResponse::table . '.' . SocialAppreciationResponse::response_type_id)    
                ->select(
                        //SocialAppreciation::table.".".SocialAppreciation::slug. ' AS aprSlug',
                        SocialAppreciationResponse::table.".".SocialAppreciationResponse::slug. ' AS aprResponseSlug',
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS response',
                        "responseCreator.".User::slug. ' AS aprRespondingUserSlug',
                        "responseCreator.".User::name. ' AS aprRespondingUserName',
                        "responseCreator.".User::email. ' AS aprRespondingUserEmail')
                ->where(SocialAppreciation::table.'.'.SocialAppreciation::slug,'=',$request->aprSlug)
                ->get();
            //dd($appreciationResponseQueryResultsObj);

            $appreciationResponseArr = !empty($appreciationResponseQueryResultsObj)? $appreciationResponseQueryResultsObj->toArray():[];

            $appreciationCommentsQueryResultsObj = DB::table(SocialAppreciationComment::table)
                ->join(SocialAppreciation::table, SocialAppreciation::table . ".id", '=', SocialAppreciationComment::table . '.' . SocialAppreciationComment::social_appreciation_id)    
                ->join(User::table. " AS aprCreator", "aprCreator.id", '=', SocialAppreciationComment::table . '.' . SocialAppreciationComment::user_id)
                ->join(User::table. " AS commentCreator", "commentCreator.id", '=', SocialAppreciationComment::table . '.' . SocialAppreciationComment::user_id)    
                ->leftJoin(UserProfile::table.' as commentCreatorProfile', "commentCreator.id", '=', 'commentCreatorProfile.' . UserProfile::user_id)
                ->leftJoin(SocialAppreciationCommentResponse::table, SocialAppreciationCommentResponse::table . ".".SocialAppreciationCommentResponse::appreciation_comment_id, '=', SocialAppreciationComment::table . '.id')
                ->leftJoin(SocialResponseType::table, SocialResponseType::table.".id", '=', SocialAppreciationCommentResponse::table . '.' . SocialAppreciationCommentResponse::response_type_id)
                ->leftJoin(User::table. " AS commentsResponseCreator", "commentsResponseCreator.id", '=', SocialAppreciationCommentResponse::table . '.' . SocialAppreciationCommentResponse::user_id)
                ->leftJoin(SocialAppreciationComment::table. " AS SocialAppreciationCommentParent", "SocialAppreciationCommentParent.id", '=', SocialAppreciationComment::table . '.' . SocialAppreciationComment::parent_social_comment_id)
                ->select(
                        SocialAppreciation::table.".".SocialAppreciation::slug. ' AS aprSlug',
                        "aprCreator.".User::slug. ' AS aprUserSlug',
                        SocialAppreciationComment::table.".".SocialAppreciationComment::slug. ' AS aprCommentSlug',
                        "SocialAppreciationCommentParent.". SocialAppreciationComment::slug. ' AS aprParentCommentSlug',
                        SocialAppreciationComment::table.".".SocialAppreciationComment::description. ' AS comment',
                        "commentCreator.".User::slug. ' AS commentCreatorUserSlug',
                        "commentCreator.".User::name. ' AS commentCreatorUserName',
                        "commentCreator.".User::email. ' AS commentCreatorUserEmail',
                        DB::raw('concat("'. $this->s3BasePath.'", commentCreatorProfile.'. UserProfile::image_path.') as commentCreatorImageUrl'),

                        SocialAppreciationCommentResponse::table.".".SocialAppreciationCommentResponse::slug." AS commentResponseSlug",
                        SocialResponseType::table.".".SocialResponseType::response_text. ' AS commentsResponse',
                        "commentsResponseCreator.".User::slug. ' AS commentsRespondingUserSlug',
                        "commentsResponseCreator.".User::name. ' AS commentsRespondingUserName',
                        "commentsResponseCreator.".User::email. ' AS commentsRespondingUserEmail'
                        )
                ->where(SocialAppreciation::table.'.'.SocialAppreciation::slug,'=',$request->aprSlug)
                ->get();
            //dd($appreciationCommentsQueryResultsObj);

            $aggregatorArr = array();

            $appreciationCommentsQueryResultsObj->each(function($item) use (&$aggregatorArr, $user){

                $aggregatorArr[$item->aprCommentSlug]["aprCommentSlug"] = $item->aprCommentSlug;
                $aggregatorArr[$item->aprCommentSlug]["aprParentCommentSlug"] = $item->aprParentCommentSlug;
                $aggregatorArr[$item->aprCommentSlug]["comment"] = $item->comment;
                $aggregatorArr[$item->aprCommentSlug]["commentCreatorUserSlug"] = $item->commentCreatorUserSlug;
                $aggregatorArr[$item->aprCommentSlug]["commentCreatorUserName"] = $item->commentCreatorUserName;
                $aggregatorArr[$item->aprCommentSlug]["commentCreatorUserEmail"] = $item->commentCreatorUserEmail;
                $aggregatorArr[$item->aprCommentSlug]["commentCreatorImageUrl"] = $item->commentCreatorImageUrl;
                //set only if $aggregatorArr[$item->aprCommentSlug]["commentedByMe"]  is false or null 
                if( empty($aggregatorArr[$item->aprCommentSlug]["commentedByMe"]) ){
                    $aggregatorArr[$item->aprCommentSlug]["commentedByMe"] = ($user->slug == $item->commentCreatorUserSlug) ? true : false;
                }
                
                if(empty($aggregatorArr[$item->aprCommentSlug]["yourCommentResponse"])){
                    $aggregatorArr[$item->aprCommentSlug]["yourCommentResponse"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentsResponse :"";
                    $aggregatorArr[$item->aprCommentSlug]["yourCommentResponseSlug"] = ($user->slug == $item->commentsRespondingUserSlug)? $item->commentResponseSlug :null;
                }
                if(!empty($item->commentResponseSlug)){
                    $aggregatorArr[$item->aprCommentSlug]["commentResponses"][] = 
                            array(
                                "commentResponseSlug" => $item->commentResponseSlug,
                                "commentsResponse" => $item->commentsResponse,
                                "commentsRespondingUserSlug" => $item->commentsRespondingUserSlug,
                                "commentsRespondingUserName" => $item->commentsRespondingUserName,
                                "commentsRespondingUserEmail" => $item->commentsRespondingUserEmail
                            );
                } else {
                    $aggregatorArr[$item->aprCommentSlug]["yourCommentResponse"] = "";
                    $aggregatorArr[$item->aprCommentSlug]["yourCommentResponseSlug"] = null;
                    $aggregatorArr[$item->aprCommentSlug]["commentResponses"] = array();
                }
            });
            //dd($aggregatorArr);

            $completeResultsArr = array();
            $completeResultsArr["appreciationComments"] = array();
            foreach ($aggregatorArr as $key => $value) {
                $completeResultsArr['appreciationComments'][] = $value;
            }
            $completeResultsArr['appreciationResponses'] = $appreciationResponseArr;

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
     * create, update and delete appreciation comment response
     */
    public function setAppreciationCommentResponse($request)
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
                $appreciationComment = DB::table(SocialAppreciationComment::table)
                        ->where(SocialAppreciationComment::slug, '=',$request->commentSlug)
                        ->first();
                if(empty($appreciationComment)){
                    throw new \Exception("Invalid commentSlug");
                }      
                $previousAppreciationCommentResponse = SocialAppreciationCommentResponse::where(SocialAppreciationCommentResponse::user_id, '=',$user->id)
                        ->where(SocialAppreciationCommentResponse::appreciation_comment_id, '=',$appreciationComment->id)
                        ->where(SocialAppreciationCommentResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousAppreciationCommentResponse)){
                    throw new \Exception("Appreciation comment already ".$request->response."d!");
                }
                $appreciationCommentResponse   = new SocialAppreciationCommentResponse();
                $appreciationCommentResponse->{SocialAppreciationCommentResponse::slug} = Utilities::getUniqueId();
                $appreciationCommentResponse = $this->appreciationCommentResponseSetter($appreciationCommentResponse, $request, $user);
                $appreciationCommentResponse->save();
            } else if($request->action == 'update'){
                $appreciationCommentResponse = SocialAppreciationCommentResponse::where(SocialAppreciationCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($appreciationCommentResponse)){
                    throw new \Exception("Invalid commentResponseSlug");
                }
                $appreciationCommentResponse = $this->appreciationCommentResponseSetter($appreciationCommentResponse, $request, $user);
                $appreciationCommentResponse->save();
            } else if($request->action == 'delete'){

                $appreciationComment = DB::table(SocialAppreciationCommentResponse::table)
                        ->where(SocialAppreciationCommentResponse::slug, '=',$request->commentResponseSlug)
                        ->first();
                if(empty($appreciationComment)){
                    throw new \Exception("Invalid commentResponseSlug");
                }

                DB::table(SocialAppreciationCommentResponse::table)
                ->where(SocialAppreciationCommentResponse::slug, '=', $request->commentResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Appreciation comment response ".$request->action." success");
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function appreciationCommentResponseSetter($appreciationCommentResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $appreciationCommentResponse->{SocialAppreciationCommentResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        } 

        $appreciationCommentResponse->{SocialAppreciationCommentResponse::org_id}  = $organisationObj->id;
        
        $appreciationComment = DB::table(SocialAppreciationComment::table)
                ->where(SocialAppreciationComment::slug, '=',$request->commentSlug)
                ->first();
        if(empty($appreciationComment)){
            throw new \Exception("Invalid commentSlug");
        }
        $appreciationCommentResponse->{SocialAppreciationCommentResponse::appreciation_comment_id} = $appreciationComment->id;
        $appreciationCommentResponse->{SocialAppreciationCommentResponse::user_id}  = $user->id; 
        return $appreciationCommentResponse;
    }

    /*
     * create, update and delete appreciation response (like)
     */
    public function setAppreciationResponse($request)
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
                $appreciation = DB::table(SocialAppreciation::table)
                        ->where(SocialAppreciation::slug, '=',$request->aprSlug)
                        ->first();
                if(empty($appreciation)){
                    throw new \Exception("Invalid aprSlug");
                }                
                $previousAppreciationResponse = SocialAppreciationResponse::where(SocialAppreciationResponse::user_id, '=',$user->id)
                        ->where(SocialAppreciationResponse::appreciation_id, '=',$appreciation->id)
                        ->where(SocialAppreciationResponse::response_type_id, '=',$responseTypeObj->id)
                        ->first();
                if(!empty($previousAppreciationResponse)){
                    throw new \Exception("Appreciation already ".$request->response."d!");
                }
                
                $appreciationResponse   = new SocialAppreciationResponse();
                $appreciationResponse->{SocialAppreciationResponse::slug} = Utilities::getUniqueId();
                $appreciationResponse = $this->appreciationResponseSetter($appreciationResponse, $request, $user);
                $appreciationResponse->save();
            } else if($request->action == 'update'){
                $appreciationResponse = SocialAppreciationResponse::where(SocialAppreciationResponse::slug, '=',$request->aprResponseSlug)
                        ->first();
                if(empty($appreciationResponse)){
                    throw new \Exception("Invalid appreciationResponseSlug");
                }
                $appreciationResponse = $this->appreciationResponseSetter($appreciationResponse, $request, $user);
                $appreciationResponse->save();
            } else if($request->action == 'delete'){

                $appreciationResponse = DB::table(SocialAppreciationResponse::table)
                        ->where(SocialAppreciationResponse::slug, '=',$request->aprResponseSlug)
                        ->first();
                if(empty($appreciationResponse)){
                    throw new \Exception("Invalid appreciationResponseSlug");
                }
                DB::table(SocialAppreciationResponse::table)
                ->where(SocialAppreciationResponse::slug, '=', $request->aprResponseSlug)->delete();

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
        $content['data'] = array("msg"=>"Appreciation response ".$request->action." success",
            "aprResponseSlug" => $appreciationResponse->{SocialAppreciationResponse::slug});
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;
    }
    
    private function appreciationResponseSetter($appreciationResponse, $request, $user) {
        $responseTypeObj = DB::table(SocialResponseType::table)
                ->where(SocialResponseType::response_text, '=',$request->response)
                ->first();
        if(empty($responseTypeObj)){
            throw new \Exception("Invalid response ");
        }
        $appreciationResponse->{SocialAppreciationResponse::response_type_id}  = $responseTypeObj->id;

        $organisationObj = DB::table(Organization::table)
                ->where(Organization::slug, '=',$request->orgSlug)
                ->first();
        if(empty($organisationObj)){
            throw new \Exception("Invalid Organisation");
        }
        $appreciationResponse->{SocialAppreciationResponse::org_id}  = $organisationObj->id;
        
        $appreciation = DB::table(SocialAppreciation::table)
                ->where(SocialAppreciation::slug, '=',$request->aprSlug)
                ->first();
        if(empty($appreciation)){
            throw new \Exception("Invalid aprSlug");
        }
        $appreciationResponse->{SocialAppreciationResponse::appreciation_id} = $appreciation->id;
        $appreciationResponse->{SocialAppreciationResponse::user_id}  = $user->id; 
        return $appreciationResponse;        
    }
    
}