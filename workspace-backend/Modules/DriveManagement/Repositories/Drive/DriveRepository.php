<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 20/07/18
 * Time: 05:48 PM
 */

namespace Modules\DriveManagement\Repositories\Drive;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Common\Utilities\FileUpload;
use Modules\Common\Utilities\ResponseStatus;
use Modules\Common\Utilities\Utilities;
use Modules\DriveManagement\Entities\Drive;
use Modules\DriveManagement\Entities\DriveContent;
use Modules\DriveManagement\Entities\DrivePermissions;
use Modules\DriveManagement\Entities\DrivePermissionUser;
use Modules\DriveManagement\Entities\DriveType;
use Modules\DriveManagement\Http\Requests\FileUploadRequest;
use Modules\DriveManagement\Repositories\DriveRepositoryInterface;
use Modules\DriveManagement\Transformers\ListDriveContentResource;
use Modules\DriveManagement\Transformers\ListTrashedFiles;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;

class DriveRepository implements DriveRepositoryInterface
{

    protected $task;
    protected $content;


    public function __construct()
    {
        $this->content = array();
    }

    /**
     * list all drive types
     * @return array
     *
     */
    public function listDriveTypes()
    {

        $driveTypes = DB::table(DriveType::table)
            ->select(
                DriveType::slug. ' as slug',
                DriveType::name. ' as name',
                DriveType::display_name. ' as displayName'
            )
            ->get();

        return $this->content = array(
            'data'   => $driveTypes,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    /**
     * create or update drive file
     * @param Request $request
     * @return array
     */
    public function createOrUpdateFile(FileUploadRequest $request)
    {
        $user = Auth::user();
        $org  = $this->getOrganization($request->orgSlug);
        $driveType = $this->getDriveType($request->driveTypeSlug);
        $drive     = $this->getDrive($user, $driveType, $org);
        DB::beginTransaction();

        try {
            $file = $request->file('file');
            $driveContent   = $this->getDriveContent($request->folderSlug);
            $dataCollection = collect([
                'driveId'  => $drive->id,
                'userId'   => $user->id,
                'isFolder' => false,
                'parentId' => ($driveContent)? $driveContent->id : null
            ]);

            $path = Utilities::createFilePath($request->orgSlug, 'drive', $request->driveTypeSlug);

            if (!empty($driveContent)) {
                $path = $path.$this->getEnumPath($driveContent);
                $path.=$driveContent->{DriveContent::slug};
            } else {
                $path = $path.$this->getEnumPath($driveContent);
            }

            $driveUploadArray = $this->driveFileUpload($file, $path, $dataCollection);
            DriveContent::insert($driveUploadArray);
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'File Uploaded successfully'),
            'code'   => Response::HTTP_CREATED,
            'status' => ResponseStatus::OK
        );
    }

    public function driveFileUpload($files, $path, $dataCollection)
    {
        return collect($files)->map(function ($file) use ($path, $dataCollection) {
            return $this->addDriveContents($file, $dataCollection, $path, 'file');
        })->all();
    }

    public function addDriveContents($file, $dataCollection, $path, $action)
    {
        $fileName = is_file($file)? $file->getClientOriginalName() : $file;
        $fileSize = is_file($file)? filesize($file) : NULL;
        $driveContentExists = DriveContent::where(DriveContent::drive_id, $dataCollection['driveId'])
            ->where(DriveContent::file_name, $fileName)
            ->where(DriveContent::is_trashed, false);

        $pathEnum = null;
        if (!is_null($dataCollection['parentId'])) {
            $pathEnum = $this->createPathEnum($dataCollection['parentId']);
            $driveContentExists->where(DriveContent::parent_id, $dataCollection['parentId']);
        } else {
            $driveContentExists->whereNull(DriveContent::parent_id);
        }

        if ($driveContentExists->exists()) {
            $existingContent = $driveContentExists->first();
            $parentId = $dataCollection['parentId'];
            if ($existingContent->{DriveContent::is_folder}) {
                $sourceFile = "{$path}{$fileName}";
                $fileName = $this->renameSameFile($fileName, $parentId, $sourceFile);
            } else {
                $sourceFile = "{$path}{$fileName}";
                $fileName = $this->renameSameFile($fileName, $parentId, $sourceFile);
            }

        }

        if ($action == 'file') {
            $fileUpload = new FileUpload;
            $fileUpload->setPath($path);
            $fileUpload->setFile($file);
            $fileUpload->s3UploadRename($fileName);
        }


        return array(
            DriveContent::slug => Utilities::getUniqueId(),
            DriveContent::drive_id    => $dataCollection['driveId'],
            DriveContent::creator_id  => $dataCollection['userId'],
            DriveContent::file_name   => $fileName,
            DriveContent::file_path   => $path,
            DriveContent::size        => $fileSize,
            DriveContent::path_enum   => $pathEnum,
            DriveContent::is_folder   => $dataCollection['isFolder'],
            DriveContent::parent_id   => !is_null($dataCollection['parentId'])? $dataCollection['parentId']: NULL,
            'created_at' => now(),
            'updated_at' => now()
        );
    }

    /**
     * create New Folder
     * @param Request $request
     * @return array
     */
    public function createNewFolder(Request $request)
    {
        $user = Auth::user();
        $org  = $this->getOrganization($request->orgSlug);
        $driveType = $this->getDriveType($request->driveTypeSlug);
        $drive     = $this->getDrive($user, $driveType, $org);
        DB::beginTransaction();

        try {
            $folderName   = $request->folderName;
            $driveContent = $this->getDriveContent($request->folderSlug);
            $dataCollection = collect([
                'driveId' => $drive->id,
                'userId'  => $user->id,
                'isFolder' => true,
                'parentId' => ($driveContent)? $driveContent->id : null
            ]);

            $path = Utilities::createFilePath($request->orgSlug, 'drive', $request->driveTypeSlug);
            $driveContents = $this->addDriveContents($folderName, $dataCollection, $path, 'folder');
            DriveContent::insert($driveContents);
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (ValidationException $e) {
            DB::rollBack();
            $this->content['error']   =  $e->validator;
            $this->content['code']    =  Response::HTTP_UNPROCESSABLE_ENTITY;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'New Folder Created!'),
            'code'   => Response::HTTP_CREATED,
            'status' => ResponseStatus::OK
        );
    }

    /**
     * List All Drive Contents
     * @param Request $request
     * @return array
     */
    public function listDriveContents(Request $request)
    {
        try {
            $driveContentAll = $this->chooseDrive($request)->get();

            $driveContentIdArr = $driveContentAll->pluck('drive_content_id')->toArray();

            $driveContentPathEnum = $driveContentAll->pluck(DriveContent::path_enum)->first();

            $pathArray = [];
            if ($driveContentPathEnum) {
                $createPathArray = $this->createEnumPathArr($driveContentPathEnum);
                $pathArray       = $createPathArray->toArray();
            }

            $membersArr = $this->getSharedMembersByDriveId($driveContentIdArr);

            $orgSlug       = $request->orgSlug;
            $driveTypeSlug = $request->driveTypeSlug;
            $downloadPath  = "";

            $driveContentAll->map(function ($driveContent) use ($membersArr, $orgSlug, $driveTypeSlug, $pathArray, $downloadPath) {
                if ((!$driveContent->{DriveContent::is_folder})) {
                    $driveTypeSlug = (isset($driveContent->{DriveType::slug}) && $driveContent->{DriveType::slug}) ? $driveContent->{DriveType::slug} : $driveTypeSlug;
                    $s3DownloadPath = $this->downloadPath($orgSlug, $driveTypeSlug, $pathArray);
                    $downloadPath   = "{$s3DownloadPath}{$driveContent->file_name}";
                }

                $driveContent->members = isset($membersArr[$driveContent->drive_content_id]) ? $membersArr[$driveContent->drive_content_id] : [];
                $driveContent->downloadPath = $downloadPath;
                $driveContent->path         = $pathArray;
            });

        } catch (ModelNotFoundException $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  422;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }



        //dd($driveContentAll);
        $response = new ListDriveContentResource($driveContentAll);
        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function createEnumPathArr($enumStr)
    {
        return DriveContent::select(DriveContent::slug, DriveContent::file_name)
            ->whereIn('id', explode('.', $enumStr))
            ->get();
    }

    public function downloadPath($orgSlug, $driveTypeSlug, $pathArray)
    {
        $env     = env('S3_PATH');
        $s3path  = Utilities::createFilePath($orgSlug, 'drive', $driveTypeSlug);
        $s3path  = "{$env}{$s3path}";
        if (!empty($pathArray)) {
            $driveSlugArr = collect($pathArray)->pluck(DriveContent::slug);
            $strPath = implode('/', $driveSlugArr->toArray()).'/';
            $s3path  = "{$s3path}{$strPath}";
        }
        return $s3path;

    }

    public function getSharedMembersByDriveId($driveContentIdArr)
    {
        $s3BasePath = env('S3_PATH');

        $sharedMembers = DB::table(DrivePermissionUser::table)->select(
            DrivePermissionUser::table. '.' .DrivePermissionUser::drive_content_id,
            DrivePermissions::table. '.' .DrivePermissions::name .'  as permissionName',
            DrivePermissionUser::table. '.' .DrivePermissionUser::slug .'  as permissionSlug',
            User::table. '.' .User::slug .' as userSlug',
            User::table. '.' .User::name. ' as userName',
            DB::raw('concat("'.$s3BasePath.'",'.UserProfile::table. '.'. UserProfile::image_path.') as userImage')
        )
            ->join(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::user_id)
            ->join(DrivePermissions::table, DrivePermissions::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_permission_id)
            ->leftjoin(UserProfile::table, User::table. '.id', '=', UserProfile::table. '.'. UserProfile::user_id)
            ->whereIn(DrivePermissionUser::table. '.' .DrivePermissionUser::drive_content_id, $driveContentIdArr)
            ->get();

        return $sharedMembers->groupBy(DrivePermissionUser::drive_content_id);
    }


    public function chooseDrive($request)
    {
        $user       = Auth::user();
        $driveType  = $this->getDriveType($request->driveTypeSlug);

        if ($driveType->{DriveType::name} == DriveType::shared_me) {
            //DB::enableQueryLog();

            if ($request->folderSlug) {
                $driveContent = DriveContent::select(DriveContent::table. '.id', DriveContent::table. '.' .DriveContent::path_enum)
                    ->where(DriveContent::table.'.'.DriveContent::slug, $request->folderSlug)
                    ->where(DriveContent::table.'.'.DriveContent::is_folder, true)
                    ->first();

                if (empty($driveContent)) {
                    throw new \Exception("Error in Drive ", Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $raw = DB::raw("SUBSTRING_INDEX(path_enum, '.', 1)");
                $driveContentAll = DB::table(DriveContent::table)->select(
                    DriveContent::table. '.id as drive_content_id',
                    DriveContent::table. '.' .DriveContent::slug,
                    DriveContent::table. '.' .DriveContent::file_name,
                    DriveContent::table. '.' .DriveContent::path_enum,
                    DriveContent::table. '.' .DriveContent::size,
                    DriveContent::table. '.' .DriveContent::is_folder,
                    DriveContent::table. '.' .DriveContent::is_trashed,
                    DriveType::table. '.' .DriveType::slug,
                    User::table. '.' .User::slug.' as sharedUserSlug',
                    DrivePermissions::table. '.' .DrivePermissions::name.' as permission',
                    DriveContent::table. '.updated_at'
                )
                    ->join(Drive::table, Drive::table. '.id', '=', DriveContent::table. '.'. DriveContent::drive_id)
                    ->join(DriveType::table, DriveType::table. '.id', '=', Drive::table. '.'. Drive::drive_type_id)
                    ->join(DrivePermissionUser::table, DrivePermissionUser::table. '.'. DrivePermissionUser::drive_content_id, '=', $raw)
                    ->leftjoin(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::shared_by)
                    ->leftjoin(DrivePermissions::table, DrivePermissions::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_permission_id)
                    ->where(DriveContent::table. '.' .DriveContent::parent_id, $driveContent->id)
                    ->where(DrivePermissionUser::table. '.' .DrivePermissionUser::user_id, $user->id)
                    ->where(DriveContent::table. '.' .DriveContent::is_trashed, false)
                    ->groupBy(DriveContent::table. '.id');
            } else {

                $driveContentAll = DB::table(DriveContent::table)->select(
                    DriveContent::table. '.id as drive_content_id',
                    DriveContent::table. '.' .DriveContent::slug,
                    DriveContent::table. '.' .DriveContent::file_name,
                    DriveContent::table. '.' .DriveContent::path_enum,
                    DriveContent::table. '.' .DriveContent::size,
                    DriveContent::table. '.' .DriveContent::is_folder,
                    DriveContent::table. '.' .DriveContent::is_trashed,
                    DriveType::table. '.' .DriveType::slug,
                    User::table. '.' .User::slug.' as sharedUserSlug',
                    DrivePermissions::table. '.' .DrivePermissions::name.' as permission',
                    DriveContent::table. '.updated_at'
                )
                    ->join(Drive::table, Drive::table. '.id', '=', DriveContent::table. '.'. DriveContent::drive_id)
                    ->join(DriveType::table, DriveType::table. '.id', '=', Drive::table. '.'. Drive::drive_type_id)
                    ->join(DrivePermissionUser::table, DriveContent::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_content_id)
                    ->leftjoin(DrivePermissions::table, DrivePermissions::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_permission_id)
                    ->leftjoin(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::shared_by)
                    ->where(DrivePermissionUser::table. '.' .DrivePermissionUser::user_id, $user->id)
                    ->where(DriveContent::table. '.' .DriveContent::is_trashed, false)
                    ->groupBy(DriveContent::table. '.id');

            }

        } else if ($driveType->{DriveType::name} == DriveType::my_drive) {
            $org   = $this->getOrganization($request->orgSlug);
            $this->createCompanyMydriveToUser($org, $user);
            $drive = $this->getDrive($user, $driveType, $org);
            $driveContentAll = $this->getSimpleDriveContent($request->folderSlug)
                ->addSelect(
                    DriveContent::table. '.id as drive_content_id',
                    User::table. '.' .User::slug.' as sharedUserSlug'
                )
                ->leftjoin(DrivePermissionUser::table, DriveContent::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_content_id)
                ->leftjoin(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::shared_by)
                ->where(DriveContent::table. '.' .DriveContent::creator_id, $user->id)
                ->where(DriveContent::table. '.' .DriveContent::drive_id, $drive->id)
                ->where(DriveContent::table. '.' .DriveContent::is_trashed, false)
                ->groupBy(DriveContent::table. '.id');

        } else if ($driveType->{DriveType::name} == DriveType::company_drive) {
            $org  = $this->getOrganization($request->orgSlug);
            $this->createCompanyMydriveToUser($org, $user);
            $driveContentAll = $this->getSimpleDriveContent($request->folderSlug);
            $driveContentAll->addSelect(
                DriveContent::table. '.id as drive_content_id',
                User::table. '.' .User::slug.' as sharedUserSlug'
            )
                ->leftjoin(DrivePermissionUser::table, DriveContent::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::drive_content_id)
                ->leftjoin(User::table, User::table. '.id', '=', DrivePermissionUser::table. '.'. DrivePermissionUser::shared_by)
                ->where(function ($query) use ($driveType, $user, $org) {
                    $query->where(Drive::table. '.' .Drive::drive_type_id, $driveType->id)
                        ->where(Drive::table. '.' .Drive::org_id, $org->id);
                })
                ->where(DriveContent::table. '.' .DriveContent::is_trashed, false)
                ->groupBy(DriveContent::table. '.id');

        } else if ($driveType->{DriveType::name} == DriveType::trash) {

            $org  = $this->getOrganization($request->orgSlug);
            $driveContentAll = $this->getBasicDriveContents()
                ->addSelect(
                    DriveContent::table. '.id as drive_content_id'
                )
                ->where(DriveContent::table. '.' .DriveContent::creator_id, $user->id)
                ->where(DriveContent::table. '.' .DriveContent::is_trashed, true);
        }

        //search drive contents
        if ((request()->has('q')) && (request()->q)) {
            $query = request()->q;
            $driveContentAll->where(DriveContent::table. '.' .DriveContent::file_name, 'like', '%'.$query.'%');
        }

        $driveContentAll = Utilities::sort($driveContentAll);

        return $driveContentAll;
    }


    public function createCompanyMydriveToUser($org, $user)
    {
        $driveUserNotExists = DB::table(Drive::table)->where(Drive::org_id, $org->id)
            ->where(Drive::user_id, $user->id)->doesntExist();

        if ($driveUserNotExists) {
            $mydriveId      = DriveType::where(DriveType::name, DriveType::my_drive)->first()->id;
            $companyDriveId = DriveType::where(DriveType::name, DriveType::company_drive)->first()->id;

            DB::table(Drive::table)->insert([
                [Drive::slug => Utilities::getUniqueId(), Drive::org_id => $org->id, Drive::user_id => $user->id, Drive::drive_type_id => $mydriveId],
                [Drive::slug => Utilities::getUniqueId(), Drive::org_id => $org->id, Drive::user_id => $user->id, Drive::drive_type_id => $companyDriveId]
            ]);
        }
    }

    public function getSimpleDriveContent($folderSlug)
    {
        $driveContent = $this->getDriveContent($folderSlug);
        //DB::enableQueryLog();
        $driveContentAll = DB::table(DriveType::table)->select(
            DriveContent::table. '.' .DriveContent::slug,
            DriveContent::table. '.' .DriveContent::file_name,
            DriveContent::table. '.' .DriveContent::path_enum,
            DriveContent::table. '.' .DriveContent::size,
            DriveContent::table. '.' .DriveContent::is_folder,
            DriveContent::table. '.' .DriveContent::is_trashed,
            DriveContent::table. '.updated_at'
        )
            ->join(Drive::table, DriveType::table. '.id', '=', Drive::table. '.' .Drive::drive_type_id)
            ->join(DriveContent::table, Drive::table. '.id', '=', DriveContent::table. '.' .DriveContent::drive_id)
            /*->where(function ($query) use ($driveType, $user, $org) {
                $query->where(Drive::table. '.' .Drive::drive_type_id, $driveType->id)
                    ->where(Drive::table. '.' .Drive::user_id, $user->id)
                    ->where(Drive::table. '.' .Drive::org_id, $org->id);
            })*/;

        if (!empty($driveContent)) {
            $driveContentAll->where(DriveContent::table. '.' .DriveContent::parent_id, $driveContent->id);
        } else {
            $driveContentAll->whereNull(DriveContent::table. '.' .DriveContent::parent_id);
        }
        //$driveContentAll->get();
        //dd(DB::getQueryLog());
        return $driveContentAll;
    }

    public function getOrganization($slug)
    {
        return Organization::select('id')->where(Organization::slug, $slug)->firstOrFail();
    }

    public function getDriveType($slug)
    {
        $driveType = DriveType::select('id', DriveType::name)->where(DriveType::slug, $slug)->first();
        if (empty($driveType)) {
            throw new \Exception("Error in Drive Type", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $driveType;
    }

    public function getDrive($user, $driveType, $org)
    {
        return Drive::select('id')
            ->where(Drive::drive_type_id, $driveType->id)
            ->where(Drive::user_id, $user->id)
            ->where(Drive::org_id, $org->id)
            ->firstOrFail();
    }

    public function getDriveContent($slug)
    {
        return DriveContent::select('id', DriveContent::slug, DriveContent::path_enum)
            ->where(DriveContent::slug, $slug)
            ->where(DriveContent::is_folder, true)
            ->first();
    }

    public function getAllDriveContent($folderSlug)
    {
        $driveContent = $this->getDriveContent($folderSlug);

        $driveContentAll = DB::table(DriveContent::table)->select(
            DriveContent::table. '.' .DriveContent::slug,
            DriveContent::table. '.' .DriveContent::file_name,
            DriveContent::table. '.' .DriveContent::path_enum,
            DriveContent::table. '.' .DriveContent::size,
            DriveContent::table. '.' .DriveContent::is_folder,
            DriveContent::table. '.' .DriveContent::is_trashed,
            DriveContent::table. '.updated_at'
        );
            //->where(DriveContent::drive_id, $drive->id)
            //->where(DriveContent::table. '.' .DriveContent::is_trashed, false);

        if (!empty($driveContent)) {
            $driveContentAll->where(DriveContent::table. '.' .DriveContent::parent_id, $driveContent->id);
        } else {
            $driveContentAll->whereNull(DriveContent::table. '.' .DriveContent::parent_id);
        }

        return $driveContentAll;
    }

    public function getDriveContentFromSlugArr($slugs, $driveId)
    {
        return DriveContent::select(
            'id',
            DriveContent::slug,
            DriveContent::file_name,
            DriveContent::path_enum,
            DriveContent::size,
            DriveContent::is_folder,
            DriveContent::is_trashed,
            'updated_at'
        )
            ->where(DriveContent::drive_id, $driveId)
            ->where(DriveContent::is_trashed, false)
            ->whereIn(DriveContent::slug, $slugs)->get();
    }

    public function getBasicDriveContents()
    {
        return DriveContent::select(
            'id',
            DriveContent::slug,
            DriveContent::file_name,
            DriveContent::path_enum,
            DriveContent::size,
            DriveContent::is_folder,
            DriveContent::is_trashed
        );
    }

    /**
     * delete Files or Folders
     * @param Request $request
     */
    public function deleteFiles(Request $request)
    {
        $user      = Auth::user();
        $org       = $this->getOrganization($request->orgSlug);
        $driveType = $this->getDriveType($request->driveTypeSlug);
        $drive     = $this->getDrive($user, $driveType, $org);
        $driveContents = $this->getDriveContentFromSlugArr($request->deleteFiles, $drive->id);

        try {
            DB::beginTransaction();
            $deleteFileArray = collect();
            if ($driveContents->isEmpty()) {
                throw new \Exception("Wrong File Selected!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $deleteFileArray = $driveContents->map(function ($driveContent) {
                DriveContent::where(DriveContent::table. '.id', $driveContent->id)
                    ->update([DriveContent::table. '.' .DriveContent::is_trashed => 1]);

            });
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'Files Moved To Trash'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );


    }

    /**
     *
     * @param Request $request
     * @return array
     */
    public function trashedFiles(Request $request)
    {
        $user      = Auth::user();
        $org       = $this->getOrganization($request->orgSlug);

        $driveTypes = Drive::select('id')
            ->where(Drive::user_id, $user->id)
            ->where(Drive::org_id, $org->id)
            ->get();

        $driveTypesArr = $driveTypes->pluck('id')->toArray();

        $driveContents = $this->getBasicDriveContents();

        $driveContents
            ->where(DriveContent::is_trashed, true)
            ->whereIn(DriveContent::drive_id, $driveTypesArr);

        $responseDriveContents = $driveContents->get();
        $status = ResponseStatus::OK;

        if ($responseDriveContents->isEmpty()) {
            $status = ResponseStatus::NOT_FOUND;
        }

        $response = new ListTrashedFiles($responseDriveContents);

        return $this->content = array(
            'data'   => $response,
            'code'   => Response::HTTP_OK,
            'status' => $status
        );
    }

    public function rename(Request $request)
    {
        $user = Auth::user();
        $org  = $this->getOrganization($request->orgSlug);
        $driveType = $this->getDriveType($request->driveTypeSlug);
        $drive     = $this->getDrive($user, $driveType, $org);

        $basicDriveContent = $this->getBasicDriveContents()
            ->where(DriveContent::slug, $request->elementSlug)
            ->where(DriveContent::is_trashed, false)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            if ($basicDriveContent->{DriveContent::is_folder}) {
                $basicDriveContent->{DriveContent::file_name} = $request->elementRename;
                $basicDriveContent->save();
            } else {
                $path = Utilities::createFilePath($request->orgSlug, 'drive', $request->driveTypeSlug);
                $sourceFilePath  = $path.$this->getEnumPath($basicDriveContent);
                $sourceFileName  = $basicDriveContent->{DriveContent::file_name};
                $sourceFile      = $sourceFilePath . $sourceFileName;

                $extension       = $this->extractFileExtension($sourceFileName);
                $renamedFileName = "{$request->elementRename}{$extension}";
                $destinationFile = "{$sourceFilePath}{$renamedFileName}";

                //saved renamed file to database
                $basicDriveContent->{DriveContent::file_name} = $renamedFileName;
                $basicDriveContent->save();

                //rename file to s3
                $this->renameS3($sourceFile, $destinationFile);

            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  404;
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'File Renamed Successfully'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    /**
     * move folder
     * @param Request $request
     * @return array
     */
    public function move(Request $request)
    {
        $sourceDriveContents = DriveContent::where(DriveContent::is_trashed, false)
            ->whereIn(DriveContent::slug, $request->sourceSlug)->get();

        try {
            DB::beginTransaction();

            if ($sourceDriveContents->isEmpty()) {
                throw new \Exception("Wrong Source File Selected!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            collect($sourceDriveContents)->map(function ($sourceDriveContent) use ($request) {
                $this->copyOrMoveProcess($sourceDriveContent, $request, 'move');
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'Files Moved To Destination'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }


    /**
     * copy a file from source to destination folder
     * @param Request $request
     * @return array
     */
    public function copy(Request $request)
    {

        $sourceDriveContents = DriveContent::where(DriveContent::is_trashed, false)
            ->whereIn(DriveContent::slug, $request->sourceSlug)->get();

        try {
            DB::beginTransaction();

            if ($sourceDriveContents->isEmpty()) {
                throw new \Exception("Wrong Source File Selected!", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            collect($sourceDriveContents)->map(function ($sourceDriveContent) use ($request) {
                $this->copyOrMoveProcess($sourceDriveContent, $request, 'copy');
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  $e->getCode();
            $this->content['status']  = ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' => 'Files Copied To Destination'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );
    }

    public function copyOrMoveProcess($sourceDriveContent, $request, $action)
    {
        $path = Utilities::createFilePath($request->orgSlug, 'drive', $request->driveTypeSlug);
        $sourceFilePath = $path.$this->getEnumPath($sourceDriveContent);
        $sourceFileName = $sourceDriveContent->{DriveContent::file_name};

        if ($sourceDriveContent->{DriveContent::is_folder}) {
            $sourceDriveContentSlug = $sourceDriveContent->{DriveContent::slug};
            $sourceFile     = "{$sourceFilePath}{$sourceDriveContentSlug}";
        } else {
            $sourceFile     = $sourceFilePath . $sourceFileName;
        }


        $destinationDriveContent = $this->getBasicDriveContents()
            ->where(DriveContent::slug, $request->destinationSlug)
            ->where(DriveContent::is_folder, true)
            ->where(DriveContent::is_trashed, false)
            ->first();

        $destinationFilePathEnum = NULL;
        $destinationFileId       = NULL;

        //if destination is in root folder or not
        if ($destinationDriveContent) {
            $destinationFilePath = $path.$this->getEnumPath($destinationDriveContent). $destinationDriveContent->{DriveContent::slug};
            $destinationFilePathEnum = $destinationDriveContent->{DriveContent::path_enum};
            $destinationFileId       = $destinationDriveContent->id;
        } else {
            $destinationFilePath = $path.$this->getEnumPath($destinationDriveContent);
        }

        $fileName = $this->renameSameFile($sourceFileName, $destinationFileId, $sourceFile);

        $uniqId = Utilities::getUniqueId();
        $newDriveContents = $sourceDriveContent->replicate();
        $newDriveContents->{DriveContent::slug} = $uniqId;
        $newDriveContents->{DriveContent::file_name} = $fileName;
        $newDriveContents->{DriveContent::parent_id} = $destinationFileId;
        $newDriveContents->{DriveContent::path_enum} = ($destinationFilePathEnum) ? $destinationFilePathEnum. '.' .$destinationFileId : $destinationFileId;
        $newDriveContents->save();


        if ($sourceDriveContent->{DriveContent::is_folder}) {
            $this->copyOrMoveInnerDriveContents($sourceDriveContent, $newDriveContents, $path, $action);
        } else {
            if ($action == 'move') {
                $sourceDriveContent->delete();
                //$filename             = $sourceDriveContent->{DriveContent::file_name};
                $destinationFilePath .= '/'.$fileName;
                $this->renameS3($sourceFile, $destinationFilePath);

            } else {
                $destinationFilePath .= '/'.$fileName;
                $this->copyS3($sourceFile, $destinationFilePath);
            }

        }

    }


    /**
     * copy or move files or folders inside a folder
     * @param $sourceDriveContent
     * @param $newDriveContents
     * @param $path
     * @param $action
     */
    public function copyOrMoveInnerDriveContents($sourceDriveContent, $newDriveContents, $path, $action)
    {
        $raw = sprintf("%s in (SELECT id FROM %s WHERE %s = %d)",
            DriveContent::parent_id, DriveContent::table, DriveContent::parent_id, $sourceDriveContent->id);

        $union = DB::table(DriveContent::table)->where(DriveContent::is_trashed, false)
            ->whereRaw($raw);
        //DB::enableQueryLog();

        $sourceInnerDriveContents = DriveContent::where(DriveContent::is_trashed, false)
            ->where(DriveContent::parent_id, $sourceDriveContent->id)
            ->union($union)
            ->orderBy('id')
            ->get();
        //dd(DB::getQueryLog());

        $parentArray    = array();
        $parentFlag     = false;

        if ($sourceInnerDriveContents->isEmpty() && $action == 'move') {
            $sourceDriveContent->delete();
        }

        foreach($sourceInnerDriveContents as $sourceInnerDriveContent) {

            if (array_key_exists($sourceInnerDriveContent->parent_content_id, $parentArray)) {
                $newInnerFilePathEnum = $parentArray[$sourceInnerDriveContent->parent_content_id]['new_path']['path_enum'];
                $newInnerFileId = $parentArray[$sourceInnerDriveContent->parent_content_id]['new_path']['parent_content_id'];
                $parentFlag = true;
            } else {
                $newInnerFilePathEnum = $newDriveContents->{DriveContent::path_enum};
                $newInnerFileId = $newDriveContents->id;
            }

            $parentArray[$sourceInnerDriveContent->id] = array(
                'parent_content_id' => $sourceInnerDriveContent->{DriveContent::parent_id},
                'path_enum' => $sourceInnerDriveContent->{DriveContent::path_enum}
            );


            $newInnerDriveContent = $sourceInnerDriveContent->replicate();
            $newInnerDriveContent->{DriveContent::slug}     = Utilities::getUniqueId();

            if ($newInnerFilePathEnum) {
                if ($parentFlag) {
                    $pathEnum = $newInnerFilePathEnum;
                } else {
                    $pathEnum = $newInnerFilePathEnum. '.' .$newInnerFileId;
                }

            } else {
                $pathEnum = $newInnerFileId;
            }

            $newInnerDriveContent->{DriveContent::path_enum} = $pathEnum;
            $newInnerDriveContent->{DriveContent::parent_id} = $newInnerFileId;
            $newInnerDriveContent->save();

            $newPathEnum = $newInnerDriveContent->{DriveContent::path_enum} . '.' . $newInnerDriveContent->id;
            $parentArray[$sourceInnerDriveContent->id]['new_path'] = array(
                'parent_content_id' => $newInnerDriveContent->id,
                'path_enum' => $newPathEnum
            );

            $parentFlag = false;

            if (!$sourceInnerDriveContent->{DriveContent::is_folder}) {

                $filename       = $sourceInnerDriveContent->{DriveContent::file_name};
                $sourceFilePath = $path.$this->getEnumPath($sourceInnerDriveContent);
                $sourceFile     = $sourceFilePath. $filename;
                $desFile        = $path.$this->getEnumPath($newInnerDriveContent).$filename;

                if ($action == 'move') {
                    $sourceDriveContent->delete();
                    $this->renameS3($sourceFile, $desFile);
                } else {
                    $this->copyS3($sourceFile, $desFile);
                }

            }

        }
    }

    /**
     * rename existing files to (copy) when copying the same file
     * @param $fileName
     * @param $parentId
     * @return string
     */
    public function renameSameFile($fileName, $parentId, $sourceFile)
    {
        $lastestFileName = $fileName;

        //DB::enableQueryLog();
        $searchFileWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);

        $latestDriveContent = DriveContent::select(DriveContent::file_name)->where(DriveContent::parent_id, $parentId)
            ->where(DriveContent::file_name, 'like', $searchFileWithoutExt.'%')->latest()->first();
        //dd(DB::getQueryLog());

        if (!empty($latestDriveContent)) {
            $lastestFileName = $latestDriveContent->{DriveContent::file_name};
            $originalUrl     = env('S3_PATH').$sourceFile;
            $ext = pathinfo($originalUrl, PATHINFO_EXTENSION);
            $fileNamewithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $lastestFileName);
            $fileName   = $fileNamewithoutExt.'(copy)';
            if ($ext) {
                $fileName   = $fileNamewithoutExt.'(copy).'.$ext;
            }

            //$ext = $this->extractFileExtension($lastestFileName);
        }

        return $fileName;

    }

    public function extractFileExtension($fileName)
    {
        $ext = "";
        $explodedNameArr = explode('.', $fileName);
        if (!empty($explodedNameArr)) {
            $ext = '.' .end($explodedNameArr);
        }
        return $ext;
    }


    /**
     * Delete Or Restore Files or Folders
     * @param Request $request
     */
    public function delOrRestoretrashedFiles(Request $request)
    {
        $user      = Auth::user();
        $org       = $this->getOrganization($request->orgSlug);
        try {
            if ($request->action == 'restore') {
                $this->restoreFile($request->fileSlug);
            } else if ($request->action == 'delete') {
                $this->deleteDriveFolders($request->fileSlug);
            }

        } catch (\Exception $e) {
            $this->content['error']   =  $e->getMessage();
            $this->content['code']    =  Response::HTTP_OK;
            $this->content['status']  =  ResponseStatus::ERROR;
            return $this->content;
        }

        return $this->content = array(
            'data'   => array('message' =>
                ($request->action == 'restore') ? 'File Restored Succesfully' : 'File Deleted Succesfully'),
            'code'   => Response::HTTP_OK,
            'status' => ResponseStatus::OK
        );

    }

    public function deleteDriveFolders($fileSlugArr)
    {
        //DB::enableQueryLog();
        $driveContents = DB::table(DriveContent::table)->select(
            DriveContent::table. '.' .DriveContent::slug,
            DriveContent::table. '.' .DriveContent::path_enum,
            DriveContent::table. '.' .DriveContent::file_name,
            DriveContent::table. '.' .DriveContent::is_folder,
            Organization::table. '.' .Organization::slug,
            DriveType::table. '.' .DriveType::slug
        )->join(Drive::table, Drive::table. '.id', '=', DriveContent::table. '.' .DriveContent::drive_id)
         ->join(Organization::table, Organization::table. '.id', '=', Drive::table. '.' .Drive::org_id)
         ->join(DriveType::table, DriveType::table. '.id', '=', Drive::table. '.' .Drive::drive_type_id)
            ->whereIn(DriveContent::table. '.' .DriveContent::slug, $fileSlugArr)->get();


        foreach ($driveContents as $driveContent) {
            $path = Utilities::createFilePath($driveContent->{Organization::slug}, 'drive', $driveContent->{DriveType::slug});

            $sourceFilePath = $path.$this->getEnumPath($driveContent);

            if ($driveContent->{DriveContent::is_folder}) {
                $deletePath = $sourceFilePath.$driveContent->{DriveContent::slug};
                $this->deleteFolderFromS3($deletePath);
            } else {
                $deletePath = $sourceFilePath.$driveContent->{DriveContent::file_name};
                $this->deleteFileFromS3([$deletePath]);
            }

            DriveContent::select('id')->where(DriveContent::slug, $driveContent->{DriveContent::slug})->delete();
        }
    }

    /**
     * Restore Files Or Folders
     * @param $fileSlugArr
     * @param $user
     * @param $org
     * @throws \Exception
     */
    public function restoreFile($fileSlugArr)
    {
        $driveContents = DB::table(DriveContent::table)->select(
            DriveContent::table. '.id'
        )->whereIn(DriveContent::table. '.' .DriveContent::slug, $fileSlugArr)
            ->where(DriveContent::table. '.' .DriveContent::is_trashed, true)->get();

        if ($driveContents->isEmpty()) {
            throw new \Exception("Wrong File Selected!");
        }

        $driveContents->map(function ($driveContent) {
            DriveContent::where(DriveContent::table. '.id', $driveContent->id)
                ->update([DriveContent::table. '.' .DriveContent::is_trashed => 0]);

            DriveContent::where(DriveContent::table. '.' .DriveContent::path_enum, 'like', $driveContent->id.'%')
                ->update([DriveContent::table. '.' .DriveContent::is_trashed => 0]);
        });

        return;
    }

    public function copyS3($source, $destination)
    {
        $fileUpload = new FileUpload;
        $fileUpload->copy($source, $destination);
        return;
    }

    public function renameS3($source, $destination)
    {
        $fileUpload = new FileUpload;
        $fileUpload->rename($source, $destination);
        return;
    }

    public function deleteFolderFromS3($pathArr)
    {
        $fileUpload = new FileUpload;
        $fileUpload->deleteDir($pathArr);
        return;
    }

    public function deleteFileFromS3($pathArr)
    {
        $fileUpload = new FileUpload;
        $fileUpload->deleteFiles($pathArr);
        return;
    }

    public function deleteDriveFiles($slugArr)
    {
        DriveContent::whereIn(DriveContent::slug, '12817985105b5ffccdac6aa/drive/13753825775b5ffde4402e7/17908713545b601e299e5bf')->delete();
        return;
    }

    public function getParentEnum($id)
    {
        return DriveContent::select(DriveContent::path_enum)
            ->where('id', $id)->firstOrFail();
    }

    /**
     * create enum path using parent id
     * @param $parentId
     * @return string
     */
    public function createPathEnum($parentId)
    {
        $parentPath = $this->getParentEnum($parentId)->{DriveContent::path_enum};
        $parentPath = !empty($parentPath)? $parentPath. "." : "";
        return $parentPath.$parentId;
    }

    public function getEnumPath($driveContent)
    {
        if (!empty($driveContent)) {
            $createdPath = "";
            $enumPaths = NULL;
            $enumPaths    = $driveContent->{DriveContent::path_enum};

            if (!empty($enumPaths)) {
                $enumPathArr  = explode('.', $enumPaths);
                $contentSlugs = $this->getContentSlugs($enumPathArr);
                $createdPath  = implode('/', $contentSlugs);
            } else {
                return;
            }


            return $createdPath.'/';
            /*$currentFolderSlug = $driveContent->{DriveContent::slug};
            return "{$createdPath}/{$currentFolderSlug}";*/
        }

        return "";
    }

    public function getContentSlugs($idArr)
    {

        $driveContent = DriveContent::select(DriveContent::slug)
            ->whereIn('id', $idArr);

        if (!empty($idArr)) {
            $ids_ordered = implode(',', $idArr);
            $driveContent->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"));
        }

        return $driveContent->get()

            ->pluck(DriveContent::slug)
            ->toArray();
    }

}