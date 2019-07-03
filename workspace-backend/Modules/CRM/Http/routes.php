<?php

Route::group(['middleware' => 'auth:api', 'prefix' => 'crm', 'namespace' => 'Modules\CRM\Http\Controllers'], function()
{
    Route::get('/', 'CRMController@index');
    Route::get('/setLead', 'LeadController@setLead');
    Route::get('/setNote', 'LeadController@setNote');
    Route::get('/getNotes', 'LeadController@setNotes');
    
    Route::get('/getLead', 'LeadController@getLead');
    Route::get('/getLeads', 'LeadController@getLeads');
    
    Route::get('/getCustomers', 'LeadController@getCustomers');
});
