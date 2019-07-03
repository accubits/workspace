<?php

Route::group(['middleware' => ['auth:api'], 'prefix' => 'admin', 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    //license
    Route::get('/fetchAllPartnersList', 'LicenseController@fetchAllPartnersList');
    Route::post('/fetchAllOrgsFromPartner', 'LicenseController@fetchAllOrgsFromPartner');
    Route::post('/fetchAllLicense', 'LicenseController@fetchAllLicense');
    Route::post('/approveLicenseRequest', 'LicenseController@approveLicenseRequest');
    Route::post('/licenseHistory', 'LicenseController@licenseHistory');

    //organization
    Route::post('/fetchAllOrg', 'OrgController@fetchAllOrg');
    Route::post('/fetchOrgSettings', 'OrgController@fetchOrgSettings');
    Route::post('/saveOrgSettings', 'OrgController@saveOrgSettings')->middleware('task_request_transformer');

    //partner
    Route::post('/createOrDeletePartner', 'PartnerController@createPartner');
    Route::post('/fetchAllPartners', 'PartnerController@fetchAllPartners');

    //partnermanager/subadmin
    Route::post('/subadmin', 'PartnerController@subadmin');
    Route::post('/fetchSubadmin', 'PartnerController@fetchSubadmin');
    Route::post('/fetchAllSubadminRoles', 'PartnerController@fetchAllSubadminRoles');
    Route::post('/saveSubadminPermissions', 'PartnerController@saveSubadminPermissions');

    //settings

    Route::post('/fetchDashboardSettings', 'SettingsController@fetchDashboardSettings');


});
