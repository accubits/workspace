<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface CommentsRepositoryInterface
{
    public function setMessageComment(Request $request);
    
    public function setMessageCommentResponse(Request $request);
    
    public function setMessageResponse(Request $request);
    
    public function getMessageCommentsAndResponses(Request $request);
    
    public function setAnnouncementComment(Request $request);
    
    public function setAnnouncementResponse(Request $request);
    
    public function setAnnouncementCommentResponse(Request $request);
    
    public function getAnnouncementCommentsAndResponses(Request $request);

}