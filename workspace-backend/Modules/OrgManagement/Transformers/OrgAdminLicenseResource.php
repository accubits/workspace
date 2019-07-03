<?php

namespace Modules\OrgManagement\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\UserManagement\Entities\User;

class OrgAdminLicenseResource extends Resource
{

    protected $licenseCnt;
    protected $orgPartnerDetails;

    public function __construct($resource, $licenseCnt, $orgPartnerDetails)
    {
        $this->licenseCnt = $licenseCnt;
        $this->orgPartnerDetails = $orgPartnerDetails;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->licenseCnt);
        return [
            'status' => $this->when(!empty($this->{OrgLicense::license_status}), function () {
                return ($this->{OrgLicense::license_status} == 0) ? 'Expired' : 'Licensed';
            }, "Unlicensed"),
            'orgName' => ($this->orgPartnerDetails->orgName)? $this->orgPartnerDetails->orgName : null,
            'key' => isset($this->{OrgLicense::key}) ?  $this->{OrgLicense::key} : null,
            'type' => isset($this->{OrgLicenseType::name}) ? $this->{OrgLicenseType::name}: null,
            'users' => [
                'totalUsers'    => isset($this->{OrgLicense::max_users}) ? $this->{OrgLicense::max_users} : null,
                'licensedUsers' => isset($this->orgId) ? $this->orgLicenseUserCount($this->orgId) : null
            ],
            'partner' => ($this->orgPartnerDetails->partnerName) ? $this->orgPartnerDetails->partnerName : null,
            'partnerImage' => ($this->orgPartnerDetails->partnerImage) ? $this->orgPartnerDetails->partnerImage : null,
            'requestedOn' => isset($this->requestedOn) ? $this->requestedOn : null,
            'startedOn' => isset($this->startedOn) ? $this->startedOn: null,
            'expiresOn' => $this->when(!empty($this->expiresOn), function () {
                return $this->expiresOn;
            }, null),

            'licenseButton' => $this->when($this->licenseCnt, function () {

                if (!isset($this->{OrgLicense::license_status}) && $this->licenseCnt > 0) {
                    return [
                        'status' => 'awaiting',
                        'message' => 'License is not activated!'
                    ];
                } else if (isset($this->{OrgLicense::license_status}) && ($this->{OrgLicense::license_status} == 0 )
                    && $this->licenseCnt > 0) {
                    return [
                        'status' => 'awaiting',
                        'message' => 'License is not activated!'
                    ];
                } else if (isset($this->{OrgLicense::license_status}) && ($this->{OrgLicense::license_status} == 1 )
                    && $this->licenseCnt > 0) {
                    return [
                        'status' => 'renew',
                        'message' => ''
                    ];
                }
            },
                [
                    'status' => 'request',
                    'message' => ''
                ]
            )
        ];
    }

    public function orgLicenseUserCount($orgId)
    {
        $loggedUserId = Auth::user()->id;
        return DB::table(OrgEmployee::table)
            ->where(OrgEmployee::table. '.' .OrgEmployee::org_id, $orgId)
            ->where(OrgEmployee::table. '.' .OrgEmployee::user_id, '<>', $loggedUserId)
            ->count();
    }
}
