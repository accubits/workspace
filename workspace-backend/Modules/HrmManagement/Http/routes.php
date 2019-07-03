<?php

use Modules\UserManagement\Entities\Roles;
use Modules\UserManagement\Entities\Permissions;

Route::group(['middleware' => ['auth:api'], 'prefix' => 'hrm', 'namespace' => 'Modules\HrmManagement\Http\Controllers'], function() {
    
    Route::post('/setKraModule', 'KraModuleController@setKraModule');
    
    Route::post('/getKraModule', 'KraModuleController@getKraModule');
    
    Route::post('/setTrainingRequest', 'TrainingController@setTrainingRequest');
    
    Route::post('/setTrainingRequestStatus', 'TrainingController@setTrainingRequestStatus');
    
    Route::post('/getTrainingRequestList', 'TrainingController@getTrainingRequestList');
    
    Route::post('/setTrainingBudget', 'TrainingController@setTrainingBudget');

    Route::post('/setTrainingFeedbackDuration', 'TrainingController@setTrainingFeedbackDuration');
    
    Route::post('/setTrainingStatus', 'TrainingController@setTrainingStatus');

    Route::post('/setTrainingScore', 'TrainingController@setTrainingScore');
    
    Route::post('/getTrainingSettings', 'TrainingController@getTrainingSettings');
    
    Route::post('/setAppraisalCycle', 'AppraisalCycleController@setAppraisalCycle');
    
    Route::post('/getAppraisalCycle', 'AppraisalCycleController@getAppraisalCycle');
    
    Route::post('/getAppraisalCycles', 'AppraisalCycleController@getAppraisalCycles');

    $groupMultipleRoles = sprintf("role:%s|%s|%s", Roles::SUPER_ADMIN, Roles::ORG_EMPLOYEE, Roles::ORG_ADMIN);

    Route::group(['middleware' => $groupMultipleRoles], function () {
        $permissions = sprintf("permission:%s|%s", Permissions::FULL_PERMISSION, Permissions::ORG_EMPLOYEE_PERMISSION);

        Route::post('clock-status', 'TimeReportController@clockInOut');

        Route::post('fetch-workday', 'TimeReportController@fetchWorkDay');

        Route::post('save-worktime', 'TimeReportController@saveWorkingDay');

        Route::post('get-current-clock-status', 'TimeReportController@getCurrentClockStatus');

        Route::post('clockout-previous-day', 'TimeReportController@clockOutPreviousDay');

        //reports

        Route::post('time-reports', 'ReportsController@fetchWorkTimeReports');

        //need to add in apiary
        Route::post('daily-report-details', 'ReportsController@fetchOneReport');

        Route::post('confirm-daily-report', 'ReportsController@confirmDailyReport');

        Route::post('fetchSingleAbsentDetails', 'ReportsController@fetchSingleAbsentDetails');

        //workreport popup
        Route::post('initiateWorkReportBeforeSubmit', 'ReportsController@initiateWorkReportBeforeSubmit');

        Route::post('listAllTasksForWorkReport', 'ReportsController@listAllTasksWorkReport');

        Route::post('listAllEventsForWorkReport', 'ReportsController@listAllEventsWorkReport');

        Route::post('sendWorkReport', 'ReportsController@sendWorkReport');

        Route::post('fetch-work-report', 'ReportsController@fetchWorkReport');

        Route::post('confirmWorkReport', 'ReportsController@confirmWorkReport');

        Route::post('apply-score', 'ReportsController@addOrChangeScore');

        Route::post('fetchall-user-work-reports', 'ReportsController@fetchAllWorkReports');

        Route::post('workReportFrequencySettings', 'ReportsController@setWorkReportFrequency');

        //calender
        Route::post('fetchAllCalender', 'CalenderController@fetchAllCalender');

        //hrm leave
        Route::post('createLeaveType', 'HrmLeaveController@createLeaveType');

        Route::post('fetchAllLeaveTypes', 'HrmLeaveController@fetchAllLeaveTypes');

        Route::post('createOrUpdateHoliday', 'HrmLeaveController@createHoliday');

        Route::post('fetchAllHolidays', 'HrmLeaveController@fetchAllHolidays');

        Route::post('createAbsent', 'HrmLeaveController@createAbsent');

        Route::post('createOrCancelLeaveRequest', 'HrmLeaveController@createOrCancelLeaveRequest');

        Route::post('fetchAllLeaveRequest', 'HrmLeaveController@fetchAllLeaveRequest');

        Route::post('approveLeaveRequest', 'HrmLeaveController@approveLeaveRequest');

        Route::post('fetchUserLeaveTypes', 'HrmLeaveController@fetchUserLeaveTypes');

        Route::post('absentChart', 'HrmLeaveController@absentChart');

    });

});
