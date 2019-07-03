<?php

use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\Permissions;
use Modules\UserManagement\Entities\User;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'usermanagement', 'namespace' => 'Modules\UserManagement\Http\Controllers'], function()
{

    Route::post('change-password', 'UserController@resetPassword');
    Route::post('logout', 'UserController@logout');


    $groupMultipleRoles = sprintf("role:%s|%s", Roles::SUPER_ADMIN, Roles::PARTNER);

    Route::group(['middleware' => $groupMultipleRoles], function()
    {
        $permissions = sprintf("permission:%s|%s", Permissions::FULL_PERMISSION, Permissions::PARTNER_PERMISSION);


    });

    Route::group(['middleware' => 'role:'. Roles::SUPER_ADMIN. '|' .Roles::ORG_EMPLOYEE. '|' .Roles::ORG_GROUP_EMPLOYEE.
    '|' .Roles::PARTNER. '|' .Roles::PARTNER_MANAGER. '|' .Roles::ORG_ADMIN], function()
    {
        Route::resource('users', 'UserController', ['only' => ['index']])
            ->middleware(['permission:'. Permissions::FULL_PERMISSION]);




        Route::get('get-profile', 'UserController@getProfile');

        Route::post('edit-profile', 'UserController@editProfile')

            ->middleware(['permission:'. Permissions::PARTNER_PERMISSION. '|' .Permissions::ORG_EMPLOYEE_PERMISSION
                . '|' .Permissions::PARTNER_MANAGER_PERMISSION,
                'task_request_transformer']);
    
            Route::delete('delete-profileimg', 'UserController@deleteProfileImg')

            ->middleware(['permission:'. Permissions::PARTNER_PERMISSION. '|' .Permissions::ORG_EMPLOYEE_PERMISSION
                . '|' .Permissions::PARTNER_MANAGER_PERMISSION]);
    
        });
});



Route::group(['middleware' => 'api', 'namespace' => 'Modules\UserManagement\Http\Controllers'], function()
{
    Route::post('forgot-password', 'UserController@forgotPassword');
    //Route::get('password-reset-redirection/{token}', 'UserController@resetPasswordRedirect');
    Route::post('password-reset-link', 'UserController@resetPasswordLink');


    Route::get('password-reset-redirection/{token}', function($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user)
            return redirect(env('RESET_PASSWORD_ERROR_LINK'));

        return redirect(env('RESET_PASSWORD_SUCCESS_LINK'). '/' .$token);
    });

    Route::get('verify/{token}', 'UserController@verifyUser');

    Route::post('verifyAndChangePassword', 'UserController@verifyAndChangePassword');

    Route::post('login', 'UserController@login');

    Route::post('info', 'UserController@prelogin');
});