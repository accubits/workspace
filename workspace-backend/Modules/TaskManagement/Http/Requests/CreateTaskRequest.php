<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskChecklists;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\UserManagement\Entities\User;

class CreateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [
            Task::title => 'required',
            Task::description => 'required',
            Task::end_date => 'required',
            //'participants' => 'required|'. 'exists:' .User::table. ','.User::slug,
            //'org_slug' => 'required|'. 'exists:' .Organization::table. ','.Organization::slug
            'org_slug' => 'required'
        ];

        if (($this->has(Task::start_date)) && ($this->start_date != NULL)) {
            //$validationList[Task::start_date] = 'after_current_utc';
            $validationList[Task::end_date]   = 'required|end_date_twenty_mins_after_start_date_utc:'. request()->start_date. '|compare_start_end_date:'. request()->start_date;
        }

        if ($this->has('checklist')) {
            $validationList['checklist.*.' . TaskChecklists::description] = 'required';
            $validationList['checklist.*.' . 'is_checked'] = 'boolean';
        }

        if ($this->hasFile('file')) {
            $validationList['file.*'] = 'max:5000';
        }



/*        if ($this->has('responsible_person')) {
            $validationList['responsible_person'] = 'exists:' .User::table. ','.User::slug;
        }*/

/*        if ($this->has('parent_task')) {
            $validationList['parent_task'] = 'exists:' .Task::table. ','.Task::slug;
        }*/

        if ($this->has('responsible_person_change_time')) {
            $validationList['responsible_person_change_time'] = 'boolean';
        }

        if ($this->has(Task::approve_task_completed)) {
            $validationList[Task::approve_task_completed] = 'boolean';
        }

        if ($this->has(Task::priority)) {
            $validationList[Task::priority] = 'boolean';
        }

        if ($this->has(Task::favourite)) {
            $validationList[Task::favourite] = 'boolean';
        }

        if ($this->has(Task::is_template)) {
            $validationList[Task::is_template] = 'boolean';
        }

        if (($this->has('reminder')) && ($this->reminder != NULL)) {
            $validationList['reminder'] = 'after_current_utc|before_twenty_mins_end_date_utc:'. request()->end_date;
        }

        if ($this->has('repeatable') && ($this->repeatable != NULL)) {
            $validationList['repeatable.'. TaskRepeat::repeat_every] = "required";

            if ($this->has('repeatable.ends')) {
                if ($this->has('repeatable.ends.never'))
                    $validationList['repeatable.ends.never'] = "boolean|nullable";

                if ($this->has('repeatable.ends.on')){
                    $validationList['repeatable.ends.on'] = 'compare_start_end_date:'. request()->start_date. '|nullable';
                }

                if ($this->has('repeatable.ends.after'))
                    $validationList['repeatable.ends.after'] = "numeric|nullable";
            }
        }

        return $validationList;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'checklist.*.' . TaskChecklists::description . '.required' => 'The checklist description field is required',
            'checklist.*.' . 'is_checked' . '.boolean' => "The checklist is_checked field must be true or false",
            'after_current_utc' => ":attribute should not less than current datetime",
            'compare_start_end_date' => ":attribute should not less than start date",
            'before_twenty_mins_end_date_utc' => "Reminder should be 20 minutes before the Due Date",
            'end_date_twenty_mins_after_start_date_utc' => 'Due Date should be 20 minutes greater than start date',
            'file.*.max' => 'File cant be greater than 5MB',
        ];
    }

    public function attributes()
    {
        return [
            'file.*' => 'file'
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
