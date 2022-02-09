<?php

namespace App\Http\Requests\LeavePolicy;

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
            'company_id' => 'required|max:50',
            'name' => 'required|max:50',
            'abbreviation' => 'required|max:10',
            'description' => 'required|max:125',
            'color' => 'sometimes|max:50',
            'document_required' => 'required|boolean',
            'reason_required' => 'required|boolean',
            'half_day_option' => 'required|boolean',
            'cycle_period' => 'required|max:50',
            'eligible_amount' => 'sometimes|max:50',
            'eligible_period' => 'sometimes|max:50',
            'accrual_option' => 'required|max:50',
            'accrual_happen' => 'sometimes|max:50',
            'approval_config_id' => 'required|max:50',
            'leave_quota_amount' => 'sometimes|max:50',
            'leave_quota_unit' => 'sometimes|max:50',
            'leave_quota_category' => 'sometimes|max:50',
            'restriction_amount' => 'sometimes|max:50',
            'carry_forward_amount' => 'sometimes|max:50',
            'carry_forward_expiry' => 'sometimes|max:50',
            'leave_credit_instant_use' => 'sometimes|boolean',
            'leave_credit_expiry_amount' => 'sometimes|max:50',
            'leave_credit_expiry_period' => 'sometimes|max:50',
            'status' => 'sometimes|max:50',
            // 'layer' => 'required',
            // 'amount' => 'required',
            // 'start_year_of_service' => 'required',
            // 'end_year_of_service' => 'required'
        ];
    }
}
