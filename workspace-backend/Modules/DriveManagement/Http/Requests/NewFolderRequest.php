<?php

namespace Modules\DriveManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewFolderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'folderName' => 'required',
            'driveTypeSlug' => 'required',
            'orgSlug' => 'required'
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
