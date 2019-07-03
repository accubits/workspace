<?php

Route::group(['middleware' => ['auth:api'], 'prefix' => 'social', 'namespace' => 'Modules\SocialModule\Http\Controllers'], function()
{
    Route::post('message', 'MessageController@setMessage');
    Route::post('announcement', 'MessageController@setAnnouncement');
    Route::post('event', 'EventController@setEvent');
    Route::post('poll', 'PollController@setPoll');
    Route::post('appreciation', 'AppreciationController@setAppreciation');

    Route::post('getEvent', 'EventController@getEvent');

    Route::post('setAnnouncementReadStatus', 'MessageController@setAnnouncementReadStatus');
    Route::post('setEventStatus', 'EventController@setEventStatus');
    Route::post('setPollAnswers', 'PollController@setPollAnswers');
    Route::post('setPollStatus', 'PollController@setPollStatus');

    Route::post('fetchActivityStream', 'ActivityStreamController@fetch');

    Route::post('messageComment', 'CommentsController@setMessageComment');
    Route::post('messageCommentResponse', 'CommentsController@setMessageCommentResponse');
    Route::post('messageResponse', 'CommentsController@setMessageResponse');
    Route::post('getMessageCommentsAndResponses', 'CommentsController@getMessageCommentsAndResponses');

    Route::post('announcementComment', 'CommentsController@setAnnouncementComment');
    Route::post('announcementResponse', 'CommentsController@setAnnouncementResponse');
    Route::post('announcementCommentResponse', 'CommentsController@setAnnouncementCommentResponse');
    Route::post('getAnnouncementCommentsAndResponses', 'CommentsController@getAnnouncementCommentsAndResponses');
    
    Route::post('appreciationComment', 'CommentsController@setAppreciationComment');
    Route::post('appreciationResponse', 'CommentsController@setAppreciationResponse');
    Route::post('appreciationCommentResponse', 'CommentsController@setAppreciationCommentResponse');
    Route::post('getAppreciationCommentsAndResponses', 'CommentsController@getAppreciationCommentsAndResponses');

    Route::post('eventComment', 'CommentsController@setEventComment');
    Route::post('eventResponse', 'CommentsController@setEventResponse');
    Route::post('eventCommentResponse', 'CommentsController@setEventCommentResponse');
    Route::post('getEventCommentsAndResponses', 'CommentsController@getEventCommentsAndResponses');
    
    Route::post('pollComment', 'CommentsController@setPollComment');
    Route::post('pollResponse', 'CommentsController@setPollResponse');
    Route::post('pollCommentResponse', 'CommentsController@setPollCommentResponse');
    Route::post('getPollCommentsAndResponses', 'CommentsController@getPollCommentsAndResponses');

    Route::post('taskWidgetActivityStream', 'ActivityStreamController@fetchTaskWidget');

});
