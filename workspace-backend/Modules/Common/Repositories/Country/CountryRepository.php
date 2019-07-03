<?php

namespace Modules\Common\Repositories\Country;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Entities\Country;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Jobs\ForgotPasswordQueue;
use Modules\Common\Repositories\CountryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Modules\UserManagement\Entities\UserProfileInterest;
use Modules\Common\Utilities\Utilities;
use Modules\OrgManagement\Entities\Organization;


class CountryRepository implements CountryRepositoryInterface
{
    protected $user;
    protected $content;
    protected $fileUpload;


    public function __construct()
    {
        // $this->user    = new User;
        // $this->content = array();
        // $this->fileUpload = $fileUpload;
    }

    public function getParams()
    {
        $page    = 1;
        $perPage = 10;

        if (request()->has('page')) {
            $page = (int)request()->page;
        }

        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $offset    = ($page * $perPage) - $perPage;

        return array('page' => $page, 'perPage' => $perPage, 'offset' => $offset);
    }

    /**
     * @return $this
     */
    public function getAllCountries()
    {

        $countryCount = DB::table(Country::table)->count();

        $country = DB::table(Country::table)
        ->select('id',Country::slug,Country::name)
            ->skip($this->getParams()['offset'])
            ->take($this->getParams()['perPage'])
            ->get();
            // dd( $country);

            $paginatedData= Utilities::paginate($country,
            $this->getParams()['perPage'], $this->getParams()['page'], array(), $countryCount
            );

//dd( $paginatedData);
            $paginatedDataArr = $paginatedData->toArray();
            $responseArr = array(
                "countries"=>$paginatedDataArr['data'],
                "total"=>$paginatedDataArr['total'],
                "to"=>$paginatedDataArr['to'],
                "from"=>$paginatedDataArr['from'],
                "currentPage"=>$paginatedDataArr['current_page']
                );
               
                
        $content = array();
        $content['data'] = $responseArr;
        $content['code'] = Response::HTTP_OK;
        $content['status'] = "OK";
        return $content;

    }

    /**
     * Add/Update/Delete Country
     * @param Request $request
     * @return array
     */
    public function setCountry(Request $request)
    {
        DB::beginTransaction();
        try {
            $validActions = array('create', 'update', 'delete');
            
            if(!in_array($request->action, $validActions)){
                throw new \Exception("action is invalid");
            }
            
            if($request->action == 'create'){
                $country = new Country;
                $country->{Country::slug} = Utilities::getUniqueId();
                $country->{Country::name} = $request->name;
                $country->{Country::is_active} = $request->isActive;
                $country->save();
                $msg = 'Country created successfully';
            } else if($request->action == 'update'){
                $country = Country::where(Country::slug, $request->slug)->first();
                if(empty($country)){
                    throw new \Exception("country slug is invalid");
                }
                $country->{Country::name} = $request->name;
                $country->{Country::is_active} = $request->isActive;
                $country->save();
                $msg = 'Country updated successfully';
            } else if($request->action == 'delete'){
                $country = Country::where(Country::slug, $request->slug)->first();
                if(empty($country)){
                    throw new \Exception("country slug is invalid");
                }
                
                // check if an organization already created under this country.
                $count = Organization::where(Organization::country_id, $country->id)->count();
                if(!empty($count)){
                    throw new \Exception($count. " organization exist under this country. cannot delete!");
                }

                $country->delete();
                $msg = 'Country deleted successfully';
            }
            
            DB::commit();

            $resp=array();
            $resp['data']   = array( 
                "msg" => $msg,
                "slug"=>$country->{Country::slug}
                );
            $resp['code']   =  200;
            $resp['status']   =  "OK";
            return $resp;

        } catch (\Exception $e) {
            DB::rollback();
            return $this->throwError($e->getMessage(), 422);
        }

    }

    
    
    public function throwError($msg, $code) : array
    {
        $resp=array();
        $resp['error'] = array('msg'=>$msg);
        $resp['code']  = $code;
        $resp['status']   =  "ERROR";
        return $resp;
    }
  
}

