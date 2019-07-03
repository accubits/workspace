<?php

namespace Modules\DriveManagement\Repositories;



use Illuminate\Http\Request;
use Modules\DriveManagement\Http\Requests\FileUploadRequest;

interface DriveRepositoryInterface
{
    public function listDriveTypes();
    public function createOrUpdateFile(FileUploadRequest $request);
    public function createNewFolder(Request $request);
    public function listDriveContents(Request $request);
    public function deleteFiles(Request $request);
    public function trashedFiles(Request $request);
    public function delOrRestoretrashedFiles(Request $request);
    public function copy(Request $request);
    public function rename(Request $request);
    public function move(Request $request);

}