<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\UserManagement\Entities\User;

class CreateTaskFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*$validationArray = [
            'filterName' => 'required|unique:' .TaskFilter::table. ',' .TaskFilter::title
        ];*/
        $validationArray = [
            'filterName' => 'required'
        ];

/*        if ($this->has('taskStatus')) {
            $validationArray['taskStatus*.'] = 'exists:' .TaskStatus::table. ','.TaskStatus::slug;
        }*/

        if ($this->has('priority') && ($this->priority != NULL)) {
            $validationArray['priority']    = 'boolean';
        }

        if ($this->has('favourite') && ($this->favourite != NULL)) {
            $validationArray['favourite']   = 'boolean';
        }

        if ($this->has('withAttachement') && ($this->withAttachement != NULL)) {
            $validationArray['withAttachement']   = 'boolean';
        }

        if ($this->has('includesSubtask') && ($this->includesSubtask != NULL)) {
            $validationArray['includesSubtask']   = 'boolean';
        }

        if ($this->has('includes_checklist') && ($this->includes_checklist != NULL)) {
            $validationArray['includesChecklist'] = 'boolean';
        }

        /*if ($this->has('participants')) {
            $validationArray['participants*.']       = 'exists:' .User::table. ','.User::slug;
        }

        if ($this->has('responsiblePerson')) {
            $validationArray['responsiblePerson*.'] = 'exists:' .User::table. ','.User::slug;
        }

        if ($this->has('createdBy')) {
            $validationArray['createdBy*.'] = 'exists:' .User::table. ','.User::slug;
        }*/




        return $validationArray;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'filter_title.unique' => "Filter Name already exists"
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
