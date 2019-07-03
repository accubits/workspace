<?php

namespace Modules\TaskManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [];

        if ($this->page) {
            $validationList['page'] = 'numeric';
        }

        if ($this->perPage) {
            $validationList['perPage'] = 'numeric';
        }

        if ($this->tab) {
            $validationList['tab'] = 'in:overview,activeTasks,setByMe,imResponsible,favourites,archive,highPriority,completed,approver';
        }

        if ($this->sortBy) {
            $validationList['sortBy'] = 'in:title,due_date,creator,created_at';
        }

        if ($this->sortOrder) {
            $validationList['sortOrder'] = 'in:asc,desc';
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
