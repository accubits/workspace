<?php
use \Modules\UserManagement\Entities\Roles;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'partnermanagement', 'namespace' => 'Modules\PartnerManagement\Http\Controllers'], function()
{
    $role = sprintf("role:%s", Roles::PARTNER_MANAGER);

    Route::group(['middleware' => $role], function () {

        Route::get('fetchAllPartners', 'PartnerManagementController@fetchAllPartners');

    });
});
