<?php

namespace Modules\HrmManagement\Repositories\KraModule;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\HrmManagement\Entities\HrmKraQuestion;
use Modules\HrmManagement\Entities\HrmKraQuestionType;
use Modules\HrmManagement\Repositories\KraModuleRepositoryInterface;
use Modules\UserManagement\Entities\UserProfile;

class KraModuleRepository implements KraModuleRepositoryInterface
{

    protected $content;
    protected $statusArray;

    public function __construct()
    {
        $this->content = array();
        $this->statusArray = array();
    }

    /**
     * create, update, delete a KRA module
     * @param Request $request
     * @return array
     */
    public function setKraModule(Request $request)
    {
        $user  = Auth::user();
        $msg = "invalid action / action missing";

        try {
            DB::beginTransaction();

            if($request->action == "create"){

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }

                if(empty($request->questions)){
                    throw new \Exception("No questions added!");
                }

                $kraModule = new HrmKraModule;
                $kraModule->{HrmKraModule::slug} = Utilities::getUniqueId();

                $kraModule->{HrmKraModule::org_id} = $organisationObj->id;
                $kraModule->{HrmKraModule::creator_user_id} = $user->id;
                $kraModule->{HrmKraModule::title} = $request->title;
                $kraModule->{HrmKraModule::description} = $request->description;
                $kraModule->save();

                $this->setMultipleQuestions($request,$kraModule,$organisationObj,$user);

                $msg = "Performance Indicator created successfully";

            } else if($request->action == "update"){

                $organisationObj = DB::table(Organization::table)
                        ->where(Organization::slug, '=',$request->orgSlug)
                        ->first();
                if(empty($organisationObj)){
                    throw new \Exception("Invalid Organisation");
                }
                $kraModule = HrmKraModule::where(HrmKraModule::slug, '=',$request->kraModuleSlug)
                        ->first();
                if(empty($kraModule)){
                    throw new \Exception("Invalid Performance Indicator Slug");
                }
                if(empty($request->questions)){
                    throw new \Exception("No questions added!");
                }                
                $kraModule->{HrmKraModule::org_id} = $organisationObj->id;
                $kraModule->{HrmKraModule::creator_user_id} = $user->id;
                $kraModule->{HrmKraModule::title} = $request->title;
                $kraModule->{HrmKraModule::description} = $request->description;
                $kraModule->save();

                $this->setMultipleQuestions($request,$kraModule,$organisationObj,$user);

                $msg = "Performance Indicator updated successfully";
            } else if($request->action == "delete"){

                $kraModule = HrmKraModule::where(HrmKraModule::slug, '=',$request->kraModuleSlug)
                        ->first();
                if(empty($kraModule)){
                    throw new \Exception("Invalid Performance Indicator Slug");
                }
                $kraModule->delete();
                $msg = "Performance Indicator deleted successfully";
            }

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  array('msg' => $e->getMessage());
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }


        return $this->content = array(
            'data'   => array(
                "msg"=>$msg,
                "kraModuleSlug"=>$kraModule->{HrmKraModule::slug}
                ),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }


    private function setMultipleQuestions($request,$kraModule,$organisationObj,$user) {
        
        $savedKraQuestionIds = array();
        foreach ($request->questions as $index => $question) {
            
            if($question['action'] == "create"){

                $kraQuestionType = DB::table(HrmKraQuestionType::table)
                        ->where(HrmKraQuestionType::type_name, '=',$question['type'])
                        ->first();
                if(empty($kraQuestionType)){
                    throw new \Exception("Invalid question type");
                }
                $kraQuestion = new HrmKraQuestion;
                $kraQuestion->{HrmKraQuestion::slug} = Utilities::getUniqueId();
                $kraQuestion = $this->setKraQuestionObj($kraQuestion, $kraModule, $kraQuestionType, $index, $question, $organisationObj,$user);
                $kraQuestion->save();
                array_push($savedKraQuestionIds, $kraQuestion->id);
            } else if($question['action'] == "update"){

                $kraQuestionType = DB::table(HrmKraQuestionType::table)
                        ->where(HrmKraQuestionType::type_name, '=',$question['type'])
                        ->first();
                if(empty($kraQuestionType)){
                    throw new \Exception("Invalid question type");
                }

                $kraQuestion = HrmKraQuestion::where(HrmKraQuestion::slug, '=',$question['questionSlug'])
                        ->first();
                if(empty($kraQuestion)){
                    throw new \Exception("Invalid question slug");
                }
                $kraQuestion = $this->setKraQuestionObj($kraQuestion, $kraModule, $kraQuestionType, $index, $question, $organisationObj,$user);
                $kraQuestion->save();
                array_push($savedKraQuestionIds, $kraQuestion->id);
            }
        }

        //delete rest
        HrmKraQuestion::where(HrmKraQuestion::kra_module_id, '=',$kraModule->id)
                        ->whereNotIn('id', $savedKraQuestionIds)
                        ->delete();
    }

    private function setKraQuestionObj($kraQuestion, $kraModule,$kraQuestionType,$index,$question,$organisationObj,$user) {
        $kraQuestion->{HrmKraQuestion::org_id} = $organisationObj->id;
        $kraQuestion->{HrmKraQuestion::kra_question_type_id} = $kraQuestionType->id;
        $kraQuestion->{HrmKraQuestion::kra_module_id} = $kraModule->id;
        $kraQuestion->{HrmKraQuestion::order_no} = $index;
        $kraQuestion->{HrmKraQuestion::question} = $question['question'];
        $kraQuestion->{HrmKraQuestion::enable_comment} = $question['enableComment'];
        $kraQuestion->{HrmKraQuestion::creator_user_id} = $user->id;
        return $kraQuestion;
    }
    
    
    
    /**
     * get KraModules
     * @return array
     */
    public function getKraModules(Request $request)
    {
        
        try {
            $s3BasePath = env('S3_PATH');
            $organisationObj = DB::table(Organization::table)
                    ->where(Organization::slug, '=',$request->orgSlug)
                    ->first();
            if(empty($organisationObj)){
                throw new \Exception("Invalid Organisation");
            }

            $kraModules = HrmKraModule::where(HrmKraModule::table. '.'. HrmKraModule::org_id, $organisationObj->id)
                    ->select(
                    HrmKraModule::table. '.' .HrmKraModule::slug.' as slug',
                    HrmKraModule::table. '.' .HrmKraModule::title.' as title',
                    HrmKraModule::table. '.' .HrmKraModule::description.' as description',
                    HrmKraQuestionType::table . '.' .HrmKraQuestionType::type_name. ' as type',
                    DB::raw("unix_timestamp(".HrmKraModule::table . '.'.HrmKraModule::CREATED_AT.") AS createdAt"),
                    User::table. '.' .User::slug.' as userSlug',
                    User::table. '.' .User::email.' as employeeEmail',
                    DB::raw('concat("'.$s3BasePath.'",employeeImage.'. UserProfile::image_path .') as employeeImage '),  
                    DB::raw('GROUP_CONCAT('. HrmKraQuestion::table. '.'. HrmKraQuestion::slug.' ORDER BY '.HrmKraQuestion::table. '.id) as questionSlugs'),
                    DB::raw('GROUP_CONCAT('. HrmKraQuestion::table. '.'. HrmKraQuestion::question.' ORDER BY '.HrmKraQuestion::table. '.id) as kraQuestions'),
                    DB::raw('GROUP_CONCAT('. HrmKraQuestionType::table. '.'. HrmKraQuestionType::type_name.' ORDER BY '.HrmKraQuestion::table. '.id) as questionTypes'),
                    DB::raw('GROUP_CONCAT('. HrmKraQuestion::table. '.'. HrmKraQuestion::enable_comment.' ORDER BY '.HrmKraQuestion::table. '.id) as enableComments'),
                    DB::raw('GROUP_CONCAT('. HrmKraQuestion::table. '.'. HrmKraQuestion::order_no.' ORDER BY '.HrmKraQuestion::table. '.id) as questionOrderNos')
                )
                ->leftJoin(HrmKraQuestion::table, HrmKraModule::table. '.id', '=', HrmKraQuestion::table. '.'. HrmKraQuestion::kra_module_id)
                ->leftJoin(HrmKraQuestionType::table, HrmKraQuestionType::table. '.id', '=', HrmKraQuestion::table. '.' .HrmKraQuestion::kra_question_type_id)
                ->leftJoin(User::table, User::table. '.id', '=', HrmKraModule::table. '.'. HrmKraModule::creator_user_id)
                ->leftJoin(UserProfile::table. ' as employeeImage', User::table. '.id', '=', 'employeeImage.' .UserProfile::user_id);
            if ($request->q) {
                $query = $request->q;
                $kraModules->Where(HrmKraModule::table. '.'. HrmKraModule::title, 'like', "%{$query}%");
            }
            $kraModules->groupBy('slug');
            
            
            if(empty($request->sortOrder)){
                $request->sortOrder = 'asc';
            }
            
            if($request->sortBy == 'title'){
                $kraModules = Utilities::sort($kraModules);
            }
            
            $kraModulesDataArr = $kraModules->get();
            
            $kraModulesDataArr->transform(function($kraMod){
                $kraModulesArr=array();
                if(!empty($kraMod->questionSlugs)){
                    $questionSlugsArr = explode(',', $kraMod->questionSlugs);
                    $questions = explode(',', $kraMod->kraQuestions);
                    $questionTypes = explode(',', $kraMod->questionTypes);
                    $enableComments = explode(',', $kraMod->enableComments);
                    $questionOrderNos = explode(',', $kraMod->questionOrderNos);
                    
                    foreach ($questionSlugsArr as $index => $questionSlug){
                        array_push($kraModulesArr, 
                                array(
                                    "questionSlug" => $questionSlug,
                                    "type"=>$questionTypes[$index],
                                    "enableComment"=>(boolean)$enableComments[$index],
                                    "question"=>$questions[$index]
                                )
                            );
                    }
                }
                $kraMod->questions = $kraModulesArr;
                unset($kraMod->questionSlugs);
                unset($kraMod->kraQuestions);
                unset($kraMod->questionTypes);
                unset($kraMod->enableComments);
                unset($kraMod->questionOrderNos);
                return $kraMod;
            });
            
            return $this->content = array(
                'data'   => array(
                        "count"=>count($kraModulesDataArr),
                        "kraModules" =>$kraModulesDataArr
                    ),
                'code'   => 200,
                'status' => "OK"
            );
        
        } catch (\Exception $e) {
            $content = array();
            $content['error']   =  array('msg' => $e->getMessage());
            $content['code']    =  422;
            $content['status']  = ResponseStatus::ERROR;
            return $content;
        }
    }
}