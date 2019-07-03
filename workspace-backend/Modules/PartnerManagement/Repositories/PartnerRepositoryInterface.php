<?php

namespace Modules\PartnerManagement\Repositories;

use Illuminate\Http\Request;
use Modules\TaskManagement\Http\Requests\TaskCommentRequest;

interface PartnerRepositoryInterface
{
    public function fetchAllPartners();
}