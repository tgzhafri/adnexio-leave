<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StaffPostRequest extends FormRequest
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
            'name' => 'required|max:100',
            'employee_no' => 'required|unique:employees,employee_no|max:50',
            'dob' => 'required|max:50',
            'employment_type' => 'required|max:50',
            'profile_photo' => 'sometimes|max:50',
            'joined_date' => 'required|max:50',
            'gender' => 'required|max:50',
            'marital_status' => 'required|max:50',
            'status' => 'required|max:50',
        ];
    }
}
