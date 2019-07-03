<?php

namespace Modules\DriveManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Utilities\ResponseStatus;
use Modules\DriveManagement\Http\Requests\FetchAllDriveContentsRequest;
use Modules\DriveManagement\Http\Requests\FileUploadRequest;
use Modules\DriveManagement\Http\Requests\NewFolderRequest;
use Modules\DriveManagement\Repositories\DriveRepositoryInterface;
use Modules\DriveManagement\Repositories\DriveShareRepositoryInterface;

class DriveManagementController extends Controller
{

    public $drive;
    public $share;

    public function __construct(DriveRepositoryInterface $drive, DriveShareRepositoryInterface $share)
    {
        $this->drive = $drive;
        $this->share = $share;
    }


    public function fetchAllDriveContents(FetchAllDriveContentsRequest $request)
    {
        $response = $this->drive->listDriveContents($request);
        return response()->json($response, $response['code']);
    }

    /**
     * Upload File To Drive
     * @param Request $request
     * @return mixed
     */
    public function uploadFile(FileUploadRequest $request)
    {
        $response = $this->drive->createOrUpdateFile($request);
        return response()->json($response, $response['code']);
    }

    /**
     * get Drive Types
     * @return mixed
     */
    public function getDriveTypes()
    {
        $response = $this->drive->listDriveTypes();
        return response()->json($response, $response['code']);
    }


    /**
     * create new folder to drive
     * @param Request $request
     * @return mixed
     */
    public function createFolder(NewFolderRequest $request)
    {
        $response = $this->drive->createNewFolder($request);
        return response()->json($response, $response['code']);
    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('drivemanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('drivemanagement::edit');
    }

    public function copy(Request $request)
    {
        $response = $this->drive->copy($request);
        return response()->json($response, $response['code']);
    }

    public function rename(Request $request)
    {
        $response = $this->drive->rename($request);
        return response()->json($response, $response['code']);
    }

    public function move(Request $request)
    {
        $response = $this->drive->move($request);
        return response()->json($response, $response['code']);
    }



    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteFiles(Request $request)
    {
        $response = $this->drive->deleteFiles($request);
        return response()->json($response, $response['code']);
    }

    public function trashed(Request $request)
    {
        $response = $this->drive->trashedFiles($request);
        return response()->json($response, $response['code']);
    }

    public function deleteOrRestore(Request $request)
    {
        $response = $this->drive->delOrRestoretrashedFiles($request);
        return response()->json($response, $response['code']);
    }

    public function share(Request $request)
    {
        $response = $this->share->sharedUsers($request);
        return response()->json($response, $response['code']);
    }


    /**
     * List All Permissions
     * @param Request $request
     * @return mixed
     */
    public function getAllPermissions(Request $request)
    {
        $response = $this->share->allPermissions($request);
        return response()->json($response, $response['code']);
    }
}
