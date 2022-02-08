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
            'icon' => 'sometimes|max:50',
            'cycle_period' => 'required|max:50',
            'accrual_option' => 'required|max:50',
            'accrual_happen' => 'required|max:50',
            'approval_config_id' => 'required|max:50',
            'carry_forward_amount' => 'sometimes|max:50',
            'carry_forward_expiry' => 'sometimes|max:50',
            'leave_credit' => 'boolean',
            'leave_credit_expiry' => 'sometimes|max:50',
            'daily_quota' => 'sometimes|max:50',
            'restriction_amount' => 'sometimes|max:50',
            'proof_required' => 'boolean',
            'description_required' => 'boolean',
            'half_day_option' => 'boolean',
            'status' => 'required|max:50',
        ];
    }
}
