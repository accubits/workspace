<?php
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\Permissions;
Route::group(['middleware' => ['auth:api'], 'prefix' => 'orgmanagement', 'namespace' => 'Modules\OrgManagement\Http\Controllers'], function()
{
    
    Route::post('/inviteEmployee', 'EmployeeController@invite');
    Route::post('/addEmployee', 'EmployeeController@store');
    Route::post('/listEmployees', 'EmployeeController@getEmployeeUsers');
    Route::post('/fetchEmployeeInfo', 'EmployeeController@fetchEmployeeInfo');
    Route::post('/fetchEmployeeLeaveInfo', 'EmployeeController@fetchEmployeeLeaveInfo');


    Route::group(['middleware' => 'role:'. Roles::PARTNER. '|' .Roles::ORG_ADMIN], function()
    {
        
        Route::resource('employee', 'EmployeeController', ['only' => ['store', 'update', 'destroy']]);

        Route::resource('employee.permissions', 'EmployeePermissionController', ['only' => ['index', 'store']])
            ->middleware(['permission:'. Permissions::PARTNER_PERMISSION]);

        Route::resource('organization', 'OrgManagementController', ['only' => ['index', 'create', 'update', 'destroy']]);

        Route::post('/fetchOrgLicense', 'LicenseController@fetchOrgLicense');

    });

    
    ////////Organisation /////////////////

    Route::post('/createOrganization', 'OrgManagementController@create');
    
    Route::post('/updateOrganization', 'OrgManagementController@update');
    
    Route::post('/deleteOrganization/{org_slug}', 'OrgManagementController@destroy');

    Route::post('/bulkDeleteOrganization', 'OrgManagementController@bulkDeleteOrg');

    Route::get('/fetchOrg/{org_slug}', 'OrgManagementController@index');

    Route::post('/fetchAllOrgs', 'OrgManagementController@listOrganization');
    
    Route::get('/fetchAllVerticals', 'OrgManagementController@listVertical');
    
    Route::post('/setVertical', 'OrgManagementController@setVertical');

    Route::post('/orgSettings', 'OrgManagementController@saveOrgSettings')
        ->middleware(['task_request_transformer']);

    Route::post('/fetchOrgSettings', 'OrgManagementController@fetchOrgSettings');

    Route::post('/orgSettingWorkReport', 'OrgManagementController@orgSettingWorkReport')
        ->middleware(['task_request_transformer']);

    //////// department ////////////////////
    
    Route::post('/fetchDepartment', 'DepartmentController@index');

    Route::post('/fetchAllDepartments', 'DepartmentController@listDepartment');
    
    Route::post('/fetchDepartmentTree', 'DepartmentController@listDepartmentTree');
    
    Route::post('/getAllDepartmentsTree', 'DepartmentController@getAllDepartmentsTree');

    Route::post('/createDepartment', 'DepartmentController@create');
    
    Route::post('/updateDepartment', 'DepartmentController@update');
    
    Route::post('/deleteDepartment', 'DepartmentController@destroy');
    
    Route::post('/setEmployeeToDepartment', 'DepartmentController@setEmployeeToDepartment');
    
    Route::post('/listDepartmentEmployees', 'DepartmentController@listDepartmentEmployees');
    
    //superadmin
    Route::post('/createLicense', 'LicenseController@create');

    //for developer only
    Route::post('/approveLicense', 'LicenseController@approveLicense');
    
    Route::post('/updateLicense', 'LicenseController@update');
    
    Route::post('/deleteLicense', 'LicenseController@deleteLicense');

    Route::get('/fetchLicense', 'LicenseController@index');

    Route::post('/fetchAllLicense', 'LicenseController@listLicense');

    Route::post('/renewLicense', 'LicenseController@renewLicense');


    //partner or organisation
    Route::post('/createLicenseRequest', 'LicenseController@createLicenseRequest');
    
    Route::post('/updateLicenseRequest', 'LicenseController@updateLicenseRequest');
    
    Route::post('/deleteLicenseRequest', 'LicenseController@deleteLicenseRequest');

    Route::post('/bulkDeleteLicenseRequest', 'LicenseController@bulkDeleteLicenseRequest');

    Route::post('/cancelLicenseRequest', 'LicenseController@cancelLicenseRequest');

    Route::get('/fetchLicenseRequest', 'LicenseController@getLicenseRequest');

    Route::post('/fetchAllLicenseRequest', 'LicenseController@listLicenseRequest');

    Route::post('/forwardLicenseRequest', 'LicenseController@forwardLicenseRequest');

    Route::post('/fetchAllPartnerLicense', 'LicenseController@fetchAllPartnerLicense');

    Route::post('/activateLicense', 'LicenseController@activateLicense');

    Route::post('/partnerDashboardSettings', 'OrgManagementController@partnerDashboardSettings')
        ->middleware(['task_request_transformer']);

    Route::post('/fetchPartnerDashboardSettings', 'OrgManagementController@fetchPartnerDashboardSettings');

    
});
