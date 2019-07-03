<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 06/08/18
 * Time: 03:21 PM
 */

namespace Modules\DriveManagement\Repositories\Drive;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\DriveContent;
use Modules\DriveManagement\Entities\DrivePermissions;
use Modules\DriveManagement\Entities\DrivePermissionUser;
use Modules\DriveManagement\Repositories\DriveShareRepositoryInterface;
use Modules\UserManagement\Entities\User;

class DriveShareRepository implements DriveShareRepositoryInterface
{

    protected $share;
    protected $content;


    public function __construct()
    {
        $this->content = array();
    }

    /**
     * @TODO -- pending delete share api
     * @param Request $request
     * @return array
     */
    public function sharedUsers($request)
    {
        $driveContentId = $this->getDriveContent($request->elementSlug)->id;

        try {
            DB::beginTransaction();
            $loggedUserId = Auth::user()->id;
            $requestSharedSlugArr = collect($request->sharedUsers)
                ->pluck('permissionSlug')->filter();

            $permissionUserSlugArr = $this->getPermissionUsers($driveContentId, $loggedUserId)
                ->whereNotIn(DrivePermissionUser::slug, $requestSharedSlugArr)
                ->delete();
            

            $requestSharedSlugArr = collect();
            collect($request->sharedUsers)->map(function ($shareUser) use ($driveContentId, $requestSharedSlugArr, $loggedUserId) {
                $requestSharedSlugArr[] = $shareUser['permissionSlug'];
                if ($shareUser['permissionSlug']) {
                    $this->addOrUpdateUsers($shareUser, $driveContentId, 'update', $loggedUserId);
                } else {
                    $this->addOrUpdateUsers($shareUser, $driveContentId, 'create', $loggedUserId);
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_UNPROCESSABLE_ENTITY;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'File shared successfully'),
            'code'   => Response::HTTP_CREATED,
            'status' => ResponseStatus::OK
        );
    }

    public function allPermissions(Request $request)
    {
        $drivePermissions = DrivePermissions::select(DrivePermissions::slug. ' as permissionSlug',
            DrivePermissions::name. ' as permissionName')->get();

        return $this->content = array(
            'data'   => $drivePermissions,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function deletePermissionUsers(array $slugArr)
    {
        return DB::table(DrivePermissionUser::table)->whereIn(DrivePermissionUser::slug, $slugArr)
            ->delete();
    }

    public function getPermissionUsers($contentId, $loggedUserId)
    {
        return DrivePermissionUser::select(DrivePermissionUser::slug)
            ->where(DrivePermissionUser::drive_content_id, $contentId)
            ->where(DrivePermissionUser::shared_by, $loggedUserId);
    }

    /**
     * add or update share people
     * @param $shareUser
     * @param $driveContentId
     * @param $action
     */
    public function addOrUpdateUsers($shareUser, $driveContentId, $action, $loggedUserId)
    {
        if ($action == 'create') {
            $userId       = $this->getUser($shareUser['userSlug'])->id;

            if ($this->permissionUserExists($userId, $driveContentId)) {
                throw new \Exception("Permission Already Exists!");
            }

            if ($this->sharedToSameSharedPersonExists($userId, $driveContentId)) {
                throw new \Exception("Resource already shared to that person!");
            }

            $drivePermissionUser = new DrivePermissionUser;
            $drivePermissionUser->{DrivePermissionUser::slug} = Utilities::getUniqueId();
            $drivePermissionUser->{DrivePermissionUser::shared_by} = $loggedUserId;
        } else {
            $userId       = $this->getUser($shareUser['userSlug'])->id;

            $drivePermissionUser = DrivePermissionUser::select('id')
                ->where(DrivePermissionUser::slug, $shareUser['permissionSlug'])
                ->firstOrFail();
        }

        $drivePermissionUser->{DrivePermissionUser::user_id} = $userId;
        $drivePermissionUser->{DrivePermissionUser::drive_permission_id} = $this->getDrivePermission($shareUser['permissionName'])->id;
        $drivePermissionUser->{DrivePermissionUser::drive_content_id} = $driveContentId;
        $drivePermissionUser->save();
    }

    public function permissionUserExists($userId, $driveContentId)
    {
        return DrivePermissionUser::select('id')
            ->where(DrivePermissionUser::user_id, $userId)
            ->where(DrivePermissionUser::drive_content_id, $driveContentId)
            ->exists();
    }

    /**
     * shared to the same shared person
     * @param $userId
     * @param $driveContentId
     * @return mixed
     */
    public function sharedToSameSharedPersonExists($userId, $driveContentId)
    {
        return DrivePermissionUser::select('id')
            ->where(DrivePermissionUser::shared_by, $userId)
            ->where(DrivePermissionUser::drive_content_id, $driveContentId)
            ->exists();
    }

    public function getDriveContent($slug)
    {
        return DriveContent::select('id')
            ->where(DriveContent::slug, $slug)->firstOrFail();
    }

    public function getDrivePermission($permissionType)
    {
        return DrivePermissions::select('id')
            ->where(DrivePermissions::type, $permissionType)->firstOrFail();
    }

    public function getUser($slug)
    {
        return User::select('id')->where(User::slug, $slug)->firstOrFail();
    }

}