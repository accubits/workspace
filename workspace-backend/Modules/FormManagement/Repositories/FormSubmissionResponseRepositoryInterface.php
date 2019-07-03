<?php

namespace Modules\FormManagement\Repositories;

use Illuminate\Http\Request;

interface FormSubmissionResponseRepositoryInterface
{

    public function getAllAnswersheet($form_slug, Request $request);
    
    public function getAnswersheet($answersheet_slug, Request $request);
    
    public function getNonPaginatedAnswersheet($answersheet_slug, Request $request);

}