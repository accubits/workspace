<?php

namespace Modules\DriveManagement\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListTrashedFiles extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'drive' => $this->collection->map(function ($collection) use ($request) {

                return [
                    'slug'  => $collection->content_slug,
                    'fileName'     => $collection->file_name,
                    'contentSize'  => $collection->content_size,
                    'isFolder'     => (bool)$collection->is_folder
                ];
            }),
            "storage" => [
                "used" => 10000000,
                "total" => 30
            ]
        ];
    }
}
