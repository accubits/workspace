<?php

namespace Modules\HrmManagement\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ClockStatusResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
