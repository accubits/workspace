<?php

namespace Modules\DriveManagement\Transformers;

use Aws\S3\S3Client;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\DriveContent;
use Modules\DriveManagement\Entities\DrivePermissions;
use Modules\DriveManagement\Entities\DrivePermissionUser;
use Modules\DriveManagement\Entities\DriveType;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class ListDriveContentResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /*$usageSize = $this->getUsageSize($request->orgSlug);
        $totalSize = 1;

        return [
            'drive' => $this->collection->map(function ($collection) use ($request) {
                //dd($collection);
                $path = collect();
                if ($collection->path_enum) {
                    $pathContents = $this->getContentSlugs(explode('.', $collection->path_enum));

                    $path = $pathContents->map(function ($pathContent) use ($path){
                        return [
                            'pathSlug' => $pathContent->content_slug,
                            'fileName' => $pathContent->file_name
                        ];
                    });
                }

                if ($collection->is_folder) {
                    $downloadPath = "";
                } else {
                    //dd(isset($collection->{DriveType::slug}));
                    $driveTypeSlug = (isset($collection->{DriveType::slug}) && $collection->{DriveType::slug}) ? $collection->{DriveType::slug} : $request->driveTypeSlug;
                    //dd($driveTypeSlug);

                    $env     = env('S3_PATH');
                    $s3path  = Utilities::createFilePath($request->orgSlug, 'drive', $driveTypeSlug);
                    $s3path  = "{$env}{$s3path}";
                    if ($path->isNotEmpty()) {
                        $strPath = implode('/', $path->pluck('pathSlug')->toArray()).'/';
                        $s3path  = "{$s3path}{$strPath}";
                    }
                    $downloadPath = "{$s3path}{$collection->file_name}";
                }

                $sharedMembers = $this->getSharedMembers($collection->drive_content_id);
                return [
                    'slug'  => $collection->content_slug,
                    'fileName'     => $collection->file_name,
                    'contentSize'  => $collection->content_size,
                    'isFolder'     => (bool)$collection->is_folder,
                    'path'         => $path,
                    'downloadPath' => $downloadPath,
                    'members' => $sharedMembers,
                    'sharedUserSlug' => isset($collection->sharedUserSlug) ? $collection->sharedUserSlug : "",
                    'permission'   => $this->getUserPermissions($collection->content_slug),
                    'modifiedDate' => Carbon::parse($collection->updated_at)->timestamp
                ];
            }),
            "storage" => [
                "used"  => $usageSize,
                'usagePercentage' => $this->calculateUsagePercent($usageSize),
                "total" => $totalSize
            ]
        ];*/


    $usageSize = $this->getUsageSize($request->orgSlug);
    $totalSize = 1;

    return [
        'drive' => $this->collection->map(function ($collection) use ($request) {

            return [
                'slug'  => $collection->content_slug,
                'fileName'     => $collection->file_name,
                'contentSize'  => $collection->content_size,
                'isFolder'     => (bool)$collection->is_folder,
                'path'         => $collection->path,
                'downloadPath' => $collection->downloadPath,
                'members' => $collection->members,
                'sharedUserSlug' => $collection->sharedUserSlug,
                'permission' => isset($collection->permission) ? $collection->permission : "",
                'modifiedDate' => Carbon::parse($collection->updated_at)->timestamp
            ];
        }),
        "storage" => [
            "used"  => $usageSize,
            'usagePercentage' => $this->calculateUsagePercent($usageSize),
            "total" => $totalSize
        ]
    ];

    }

    public function calculateUsagePercent($usageSize)
    {
        $totalSize = 1073741824;
        $percent = (($usageSize/$totalSize) * 100);
        return round($percent, 2);
    }

    public function getUsageSize($orgSlug)
    {
        $drivePath  = $orgSlug.'/drive/';

        $client = new S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);

        $size    = 0;
        $bucket  = env('AWS_BUCKET');
        $objects = $client->getIterator('ListObjects', array(
            "Bucket" => $bucket,
            "Prefix" => $drivePath
        ));

        foreach ($objects as $object) {
            $size = $size+$object['Size'];
        }
        return $size;
    }

    public function getSharedMembers($driveContentId)
    {
        $sharedMembers = DB::table(DrivePermissionUser::table)->select(
            DrivePermissions::table. '.' .DrivePermissions::name .'  as permissionName',
            DrivePermissionUser::table. '.' .DrivePermissionUser::slug .'  as permissionSlug',
            User::table. '.' .User::slug .' as userSlug',
            User::table. '.' .User::name. ' as userName',
            UserProfile::table. '.' .UserProfile::user_image . ' as userImage'
        )
            ->join(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::user_id)
            ->join(DrivePermissions::table, DrivePermissions::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_permission_id)
            ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.'. UserProfile::user_id)
            ->where(DrivePermissionUser::table. '.' .DrivePermissionUser::drive_content_id, $driveContentId)
            ->get();

        return $sharedMembers;
    }

    public function getContentSlugs($idArr)
    {
        return DriveContent::select(DriveContent::slug, DriveContent::file_name)
            ->whereIn('id', $idArr)
            ->get();
    }

    public function getUserPermissions($contentSlug)
    {
        $user = Auth::user();
        return $driveUserPermissions = DB::table(DrivePermissionUser::table)->select(DrivePermissions::table. '.' .DrivePermissions::type)
            ->join(DrivePermissions::table, DrivePermissions::table. '.id', '=', DrivePermissionUser::table. '.' .DrivePermissionUser::drive_permission_id)
            ->join(DriveContent::table, DriveContent::table. '.id', '=', DrivePermissionUser::table. '.' .DrivePermissionUser::drive_content_id)
            ->where(DrivePermissionUser::table. '.' .DrivePermissionUser::user_id, $user->id)
            ->where(DriveContent::table. '.' .DriveContent::slug, $contentSlug)
            ->pluck(DrivePermissions::table. '.' .DrivePermissions::type)
            ->first();
    }
}
