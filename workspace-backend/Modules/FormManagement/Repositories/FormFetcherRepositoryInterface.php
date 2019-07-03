<?php

namespace Modules\FormManagement\Repositories;

use Illuminate\Http\Request;

interface FormFetcherRepositoryInterface
{
    public function getAllForms(Request $request);

    public function fetch(Request $request);

    public function componentTypes();
    
    public function fetchClientForm(Request $request);
    
    public function getShareList(Request $request);
    
    public function getFormSendList(Request $request);
}