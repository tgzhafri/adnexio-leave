<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StaffPutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return $this->route('employee')->user->id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|max:100',
            'employee_no' => 'required|unique:employees,employee_no|max:50',
            'dob' => 'sometimes|max:50',
            'employment_type' => 'sometimes|max:50',
            'profile_photo' => 'sometimes|max:50',
            'joined_date' => 'sometimes|max:50',
            'gender' => 'sometimes|max:50',
            'marital_status' => 'sometimes|max:50',
            'status' => 'sometimes|max:50',
        ];
    }
}
