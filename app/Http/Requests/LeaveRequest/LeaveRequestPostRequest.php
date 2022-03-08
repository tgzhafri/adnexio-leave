<?php

namespace App\Http\Requests\LeaveRequest;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'staff_id' => 'required',
            'leave_policy_id' => 'required',
            'status' => 'sometimes',
            'reason' => 'sometimes',
            'attachment' => 'sometimes|mimes:pdf,jpeg,png,jpg|max:2048',
        ];
    }
}
