<?php

namespace Modules\DriveManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\DriveManagement\Entities\DriveContent;

class FetchAllDriveContentsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationList = [];

        if ($this->sortBy) {
            $validationList['sortBy'] = 'in:name,modified,size';
            if ($this->sortBy == 'name')
                request()->sortBy = DriveContent::file_name;

            if ($this->sortBy == 'modified')
                request()->sortBy = DriveContent::UPDATED_AT;

            if ($this->sortBy == 'size')
                request()->sortBy = DriveContent::size;
        }

        if ($this->sortOrder) {
            if (!$this->sortBy) {
                $validationList['sortBy'] = 'required';
            }
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
