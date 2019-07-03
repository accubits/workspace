<?php

Route::group(['middleware' => 'auth:api', 'prefix' => 'formmanagement', 'namespace' => 'Modules\FormManagement\Http\Controllers'], function()
{
    Route::get('/', 'FormManagementController@index');
    Route::get('/components', 'FormManagementController@componentTypes');
    Route::post('/create', 'FormManagementController@create');
    Route::post('/updateFormStatus', 'FormManagementController@updateFormStatus');
    Route::get('/fetch/{form_slug}', 'FormManagementController@fetch');
    Route::get('/fetchClientForm/{form_slug}', 'FormManagementController@fetchClientForm');
    Route::post('/fetchAllForms', 'FormManagementController@getAllForms');
    Route::post('/clientFormSubmission/{form_slug}', 'FormManagementController@clientFormSubmission');
    Route::get('/fetchAllClientFormResponses/{form_slug}', 'FormManagementController@fetchAllClientFormResponses');
    Route::get('/fetchClientFormResponse/{answersheet_slug}', 'FormManagementController@fetchClientFormResponse');
    Route::get('/fetchNonPaginatedClientFormResponse/{answersheet_slug}', 'FormManagementController@fetchNonPaginatedClientFormResponse');
    Route::post('/share', 'FormManagementController@share');
    Route::post('/getShareList', 'FormManagementController@getShareList');
    Route::post('/deleteForms', 'FormManagementController@deleteForms');
    Route::post('/sendForm', 'FormManagementController@sendForm');
    Route::post('/getSendFormList', 'FormManagementController@getSendFormList');
});

