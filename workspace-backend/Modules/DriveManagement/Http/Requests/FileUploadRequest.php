<?php

namespace Modules\DriveManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file.*' => 'required|mime_type_validation|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'file.*.mime_type_validation' => 'Invalid File Format',
            'file.*.max' => 'File cant be greater than 5 MB',
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
