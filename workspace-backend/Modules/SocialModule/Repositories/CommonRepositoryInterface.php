<?php

namespace Modules\SocialModule\Repositories;

use Illuminate\Http\Request;

interface CommonRepositoryInterface
{
    public function getLookupId($title, $attribute, $value);

    public function fetchTaskWidget($request);

    public function getUser($reqArr);
    
    public function setSocialActivityStreamTask($creatorUserObj,$organisationObj,$taskObj, $toUserIdsCollectionObj, $note);
    
    public function setSocialActivityStreamForm($creatorUserObj,$organisationObj,$formObj, $toUserIdsCollectionObj, $note);

}