<?php

namespace Modules\TaskManagement\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCommentList extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($collection) {
           return [
               'commentSlug' => $collection->slug,
               'taskParentCommentSlug' => $collection->taskParentCommentSlug,
               'description' => $collection->description,
               'createdOn' => Carbon::parse($collection->createdOn)->timestamp,
               'commentedUserName' => $collection->userName,
               'commentedUserSlug' => $collection->userSlug,
               'commentedUserImage' => $collection->userImage,
               'like' => $collection->like
           ];
        });
    }
}
