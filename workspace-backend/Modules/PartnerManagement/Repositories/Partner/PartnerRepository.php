<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 20/11/18
 * Time: 05:58 PM
 */

namespace Modules\PartnerManagement\Repositories\Partner;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Entities\Country;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\PartnerManagement\Entities\Partner;
use Modules\PartnerManagement\Entities\PartnerManager;
use Modules\PartnerManagement\Entities\PartnerManagerPartnerMap;
use Modules\PartnerManagement\Repositories\PartnerRepositoryInterface;
use Modules\UserManagement\Entities\User;

class PartnerRepository implements PartnerRepositoryInterface
{
    public function __construct()
    {
        $this->content = array();
    }

    /**
     * @return array
     */
    public function fetchAllPartners()
    {
        $loggedUser = Auth::user();
        try {
            $partners = DB::table(PartnerManagerPartnerMap::table)
                ->join(PartnerManager::table, PartnerManager::table. '.id', '=', PartnerManagerPartnerMap::table. '.' .PartnerManagerPartnerMap::partner_manager_id)
                ->join(Partner::table, Partner::table. '.id', '=', PartnerManagerPartnerMap::table. '.' .PartnerManagerPartnerMap::partner_id)
                ->join(User::table, User::table. '.id', '=', Partner::table. '.' .Partner::user_id)
                ->join(Country::table, Country::table. '.id', '=', Partner::table. '.' .Partner::country_id)
                ->where(PartnerManager::table. '.' .PartnerManager::user_id, $loggedUser->id)
                ->select(
                    User::table. '.' .User::name,
                    User::table. '.' .User::slug. ' as userSlug',
                    Partner::table. '.' .Partner::partner_slug. ' as partnerSlug',
                    Country::table. '.' .Country::name. ' as partnerCountry',
                    Partner::table. '.' .Partner::phone
                );

            $partnersCount = $partners->count();

            $partnerData = $partners
                ->skip(Utilities::getParams()['offset']) //$request['offset']
                ->take(Utilities::getParams()['perPage']) //$request['perPage']
                ->get();

            $paginatedData = Utilities::paginate($partnerData, Utilities::getParams()['perPage'], Utilities::getParams()['page'], array(), $partnersCount)->toArray();
            $response = Utilities::unsetResponseData($paginatedData);
            $response['partners'] = $paginatedData['data'];

            return $this->content = array(
                'data'   => $response,
                'code'   => Response::HTTP_OK,
                'status' => ResponseStatus::OK
            );

        } catch (QueryException $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }


    }

}