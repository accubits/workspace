<?php
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\Permissions;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'drive', 'namespace' => 'Modules\DriveManagement\Http\Controllers'], function()
{
    $groupMultipleRoles = sprintf("role:%s|%s|%s", Roles::SUPER_ADMIN, Roles::ORG_EMPLOYEE, Roles::ORG_ADMIN);

    Route::group(['middleware' => $groupMultipleRoles], function()
    {
        $permissions = sprintf("permission:%s|%s", Permissions::FULL_PERMISSION, Permissions::ORG_EMPLOYEE_PERMISSION);

        Route::post('fetchall', 'DriveManagementController@fetchAllDriveContents');

        Route::post('upload-file', 'DriveManagementController@uploadFile')
            ->middleware(['task_request_transformer']);

        Route::post('delete-files', 'DriveManagementController@deleteFiles');

        Route::post('trashed-files', 'DriveManagementController@trashed');

        Route::post('delete-restore-trashed-files', 'DriveManagementController@deleteOrRestore');

        Route::post('new-folder', 'DriveManagementController@createFolder');

        Route::post('copy-file', 'DriveManagementController@copy');

        Route::post('rename-file', 'DriveManagementController@rename');

        Route::post('move-file', 'DriveManagementController@move');

        Route::get('list-drive-types', 'DriveManagementController@getDriveTypes');

        Route::post('share', 'DriveManagementController@share');

        Route::get('list-permissions', 'DriveManagementController@getAllPermissions');
    });

});
