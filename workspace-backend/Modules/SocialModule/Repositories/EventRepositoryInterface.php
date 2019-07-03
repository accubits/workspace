<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface EventRepositoryInterface
{
    public function createEvent(Request $request);
    
    public function setEventStatus(Request $request);
    
    public function getEvent(Request $request);
}