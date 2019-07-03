<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface AnnouncementRepositoryInterface
{
    public function createAnnouncement(Request $request);
    
    public function setAnnouncementReadStatus(Request $request);

}