<?php

namespace Modules\FormManagement\Repositories;

use Illuminate\Http\Request;

interface FormSubmissionRepositoryInterface
{

    public function save($form_slug, Request $request);

}