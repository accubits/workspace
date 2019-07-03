<?php
use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\Permissions;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'taskmanagement', 'namespace' => 'Modules\TaskManagement\Http\Controllers'], function()
{
    $groupMultipleRoles = sprintf("role:%s|%s|%s", Roles::SUPER_ADMIN, Roles::ORG_EMPLOYEE, Roles::ORG_ADMIN);

    Route::group(['middleware' => $groupMultipleRoles], function()
    {
        $permissions = sprintf("permission:%s|%s", Permissions::FULL_PERMISSION, Permissions::ORG_EMPLOYEE_PERMISSION);

        Route::resource('task', 'TaskController', ['only' => ['edit', 'destroy']]);

        Route::post('taskBulkDelete', 'TaskController@taskBulkDelete');

        Route::post('favOrPriorityMultipleUpdate', 'TaskController@favOrPriorityMultipleUpdate');

        Route::post('task', 'TaskController@store')
            ->middleware(['task_request_transformer']);

        Route::post('task/{task}', 'TaskController@update')
            ->middleware(['task_request_transformer']);

        Route::post('partial-update', 'TaskController@taskPartialUpdate');

        Route::post('fetchall', 'TaskController@fetchall');

        Route::post('task-details', 'TaskController@fetch');

        Route::post('task-history', 'TaskController@getTaskHistory');


        Route::get('parent-tasks', 'TaskController@getParentTasks');

        Route::get('task-templates', 'TaskController@getTemplates');

        Route::post('subtasks', 'TaskController@getTaskSubtask');

        Route::post('update-task-status', 'TaskController@updateTaskStatus');

        Route::get('list-task-status', 'TaskController@listTaskStatus');

        Route::post('addToArchive', 'TaskController@addToArchive');

        Route::post('changeChecklistStatus', 'TaskController@changeChecklistStatus');

        /** Task Comments */

        Route::resource('comment', 'TaskCommentController', ['only' => ['store']]);

        Route::get('task/{task}/comments', 'TaskCommentController@index');

        Route::post('fetchComment', 'TaskCommentController@fetchComment');

        Route::post('updateComment', 'TaskCommentController@updateComment');

        Route::post('comment/{comment}/like', 'TaskCommentController@like');

        Route::post('taskCommentDelete', 'TaskCommentController@taskCommentDelete');

        /** TasK Filter */

        Route::resource('task-filter', 'TaskFilterController', ['only' => ['store', 'destroy', 'edit']]);

        Route::post('task-filter-update', 'TaskFilterController@update');

        Route::post('list-task-filter', 'TaskFilterController@index');

    });

    Route::post('orguserlist', 'TaskController@getOrgUsers');
});
