<?php

Route::group(['middleware' => 'web', 'prefix' => 'common', 'namespace' => 'Modules\Common\Http\Controllers'], function()
{
    Route::get('/', 'CommonController@index');
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'common', 'namespace' => 'Modules\Common\Http\Controllers'], function()
{
    Route::get('country', 'CountryController@getCountry');
    Route::post('setCountry', 'CountryController@setCountry');
    Route::post('getRoleDetails', 'CommonController@getRoleDetails');

});
