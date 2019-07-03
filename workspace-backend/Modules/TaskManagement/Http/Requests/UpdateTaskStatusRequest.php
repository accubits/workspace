<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\TaskManagement\Entities\Task;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [];

        if ($this->action) {
            $validationList['action'] = 'in:single';
        }

        if (($this->tasks) && ($this->action == 'single')) {
            $validationList['tasks'] = 'array|max:1|exists:'.Task::table.','.Task::slug;
        }

/*        if (($this->tasks) && ($this->action == 'multiple')) {
            $validationList['tasks'] = 'array|min:1|exists:'.Task::table.','.Task::slug;
        }*/

        if ($this->status) {
            $validationList['status'] = 'in:complete,start,pause,accepted,returnTask';
        }

        return $validationList;
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
