<?php

namespace Modules\HrmManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddHolidayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [];
        $validationList['action'] = 'in:create,update,delete';

        if ($this->action != 'delete') {
            $validationList['name']   = 'required';
            $validationList['description']   = 'required';
            $validationList['holidayDate']   = 'required';
        }

        if ($this->has('action') && $this->action == 'update') {
            $validationList['holidaySlug'] = 'required';
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
