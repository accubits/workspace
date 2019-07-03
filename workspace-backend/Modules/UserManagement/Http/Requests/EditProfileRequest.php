<?php

namespace Modules\UserManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\UserManagement\Entities\UserProfile;

class EditProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            UserProfile::first_name => "required"
        ];
    }

    public function messages()
    {
        return [
            UserProfile::first_name. '.required' => 'Name field is required'
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
