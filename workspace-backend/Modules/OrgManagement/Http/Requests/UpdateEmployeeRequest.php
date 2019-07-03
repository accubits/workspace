<?php

namespace Modules\OrgManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            User::name  => 'required'

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
