<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;

class createRequestBulkDelete extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orgSlug' => 'required',
            'taskSlugs' => 'required|'. 'exists:' .Task::table. ','.Task::slug
        ];
    }

    public function messages()
    {
        return [
            'taskSlugs.exists'  => 'Invalid Task'
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
