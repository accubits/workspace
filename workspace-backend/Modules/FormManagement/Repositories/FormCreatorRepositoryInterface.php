<?php

namespace Modules\FormManagement\Repositories;

use Illuminate\Http\Request;

interface FormCreatorRepositoryInterface
{

    public function create(Request $request);
    public function updateFormStatus(Request $request);
    public function share(Request $request);
    public function deleteForms(Request $request);

}