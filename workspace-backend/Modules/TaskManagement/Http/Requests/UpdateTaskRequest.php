<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Modules\Common\Utilities\Utilities;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskChecklists;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\UserManagement\Entities\User;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [];

        $validationList = [
            Task::title => 'required',
            Task::description => 'required',
            Task::end_date => 'required',
            'org_slug' => 'required'
        ];

        if (($this->has(Task::start_date)) && ($this->start_date != NULL)) {
            //$validationList[Task::start_date] = 'after_current_utc';
            //$validationList[Task::end_date]   = 'required|compare_start_end_date:'. request()->start_date;
            $validationList[Task::end_date]   = 'required|end_date_twenty_mins_after_start_date_utc:'. request()->start_date. '|compare_start_end_date:'. request()->start_date;
        }

        if ($this->has('checklist')) {
            $validationList['checklist.*.' . TaskChecklists::description] = 'required';
            $validationList['checklist.*.' . 'is_checked'] = 'boolean';
        }

        if ($this->hasFile('file')) {
            $validationList['file.*'] = 'max:5000';
        }


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



/*        if ($this->has('action'))
            $validationList['action'] = 'in:partial_update,update';

        if ($this->has('action') == 'partial_update') {
            if ($this->has('start_date'))
                $validationList[Task::start_date] = 'after_current_utc';

            if ($this->has('end_date')) {
                if ($this->has('start_date'))
                    $validationList[Task::end_date] = 'required|compare_start_end_date:'. request()->start_date;
                else {
                    $task = DB::table(Task::table)->select(Task::start_date)
                        ->where(Task::slug, request()->task)
                        ->first();
                    if ($task->start_date) {
                        $ts = Utilities::createDateTimeToUTC('Y-m-d H:i:s', $task->{Task::start_date});
                        $validationList[Task::end_date] = 'required|compare_start_end_date:'. $ts->getTimestamp();
                    }

                }

            }


            if ($this->has('participants'))
                $validationList['participants']   = 'required|'. 'exists:' .User::table. ','.User::slug;

        } else {
            $validationList = [
                Task::title => 'required',
                Task::description => 'required',
                Task::end_date => 'required|date_format:d/m/Y H:i:s|after:'. Task::start_date,
                'participants' => 'required|'. 'exists:' .User::table. ','.User::slug
            ];

            if ($this->has(Task::start_date)) {
                $validationList[Task::start_date] = 'after_current_utc';
                $validationList[Task::end_date]   = 'required|compare_start_end_date:'. request()->start_date;
            }
        }


        if ($this->has('checklist')) {
            $validationList['checklist.*.' . TaskChecklists::description] = 'required';
            $validationList['checklist.*.' . 'is_checked'] = 'required|boolean';
        }


        if ($this->has('task_status')) {
            $validationList['task_status'] = "in:start,pause,complete";
        }

        if ($this->has('responsible_person_change_time')) {
            $validationList['responsible_person_change_time'] = 'boolean';
        }

        if ($this->has(Task::approve_task_completed)) {
            $validationList[Task::approve_task_completed] = 'boolean';
        }

        if ($this->has('reminder')) {
            $validationList['reminder'] = 'after_current_utc';
        }

        if ($this->has('repeatable')) {
            $validationList['repeatable.'. TaskRepeat::repeat_every] = "required";

            if ($this->has('repeatable.ends')) {
                if ($this->has('repeatable.ends.never'))
                    $validationList['repeatable.ends.never'] = "required|boolean";

                if ($this->has('repeatable.ends.on'))
                    $validationList['repeatable.ends.on'] = "required|compare_start_end_date:". request()->start_date;

                if ($this->has('repeatable.ends.after'))
                    $validationList['repeatable.ends.after'] = "required|numeric";
            }
        }

        return $validationList;
    }*/

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
            'before_twenty_mins_end_date_utc' => "reminder should be 20 minutes before the due date",
            'compare_start_end_date' => ":attribute should not less than start date"
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
