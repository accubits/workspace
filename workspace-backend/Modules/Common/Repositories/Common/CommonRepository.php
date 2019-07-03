<?php

namespace Modules\Common\Repositories\Common;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Repositories\CommonRepositoryInterface;
use Modules\PartnerManagement\Entities\Partner;
use Modules\UserManagement\Entities\Roles;


class CommonRepository implements CommonRepositoryInterface
{
    protected $user;
    protected $content;


    public function __construct()
    {
    }

    public function getRoleDetails(Request $request)
    {
        $loggedUser = Auth::user();

        try {

            $partnerSlug = NULL;
            if ($loggedUser->hasRole(Roles::PARTNER)) {
                $partner = DB::table(Partner::table)
                    ->select(Partner::partner_slug)
                    ->where(Partner::user_id, $loggedUser->id)->first();

                $partnerSlug = $partner->{Partner::partner_slug};
            }

            $formatedData = array('partnerSlug' => $partnerSlug);
            $responseData['status'] = 'OK';
            $responseData['data']['roleDetails'] = $formatedData;
            $responseData['code'] = Response::HTTP_OK;
            return $responseData;


        } catch (QueryException $e) {
            return $this->throwError($e->getMessage(), 422);
        } catch (\Exception $e) {
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

