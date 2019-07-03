<?php

namespace Modules\TaskManagement\Repositories;

use Illuminate\Http\Request;

interface TaskFilterRepositoryInterface
{
    public function createFilter(Request $request, $action);
    public function deleteFilter(Request $request);
    public function editFilter(Request $request);
    public function listFilters(Request $request);
}