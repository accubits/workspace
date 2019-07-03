<?php

namespace Modules\HrmManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClockStatusRequest extends FormRequest
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
            'status'  => 'in:clockIn,clockOut,pause,clockContinue,earlyClockout',
            'currentTime' => 'required'
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
