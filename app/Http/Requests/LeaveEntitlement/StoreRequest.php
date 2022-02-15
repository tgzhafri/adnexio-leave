<?php

namespace App\Http\Requests\LeaveEntitlement;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'leave_policy_id' => 'integer|required',
            'layer' => 'integer|required|',
            'amount' => 'integer|required',
            'start_year_of_service' => 'integer|required',
            'end_year_of_service' => 'integer|required',
        ];
    }
}
