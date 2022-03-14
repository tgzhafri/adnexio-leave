<?php

namespace App\Http\Requests\LeavePolicy;

use App\Enums\AccrualType;
use App\Enums\LeavePeriodType;
use App\Enums\LeavePolicyType;
use App\Enums\QuotaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LeavePolicyPostRequest extends FormRequest
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

    public function wantsJson()
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
            // 'company_id' => 'sometimes|max:50',
            // 'approval_route_id' => 'required|max:50',
            'name' => 'required|max:50',
            'abbreviation' => 'required|max:10',
            'description' => 'required|max:125',
            'color' => 'sometimes|max:50',
            'type' => 'required|enum_value:' . LeavePolicyType::class,
            'attachment_required' => 'required|boolean',
            'reason_required' => 'required|boolean',
            'half_day_option' => 'required|boolean',
            'cycle_period' => 'required|enum_value:' . LeavePeriodType::class,
            'eligible_amount' => 'sometimes|max:50',
            'eligible_period' => 'sometimes|nullable|enum_value:' . LeavePeriodType::class,
            'accrual_type' => 'required|enum_value:' . AccrualType::class,
            'accrual_happen' => 'sometimes|nullable|enum_value:' . LeavePeriodType::class,
            'quota_amount' => 'sometimes|max:50',
            'quota_unit' => 'sometimes|nullable|enum_value:' . QuotaType::class,
            'quota_category' => 'sometimes|nullable|enum_value:' . QuotaType::class,
            'quota_category' => 'sometimes|max:50',
            'restriction_amount' => 'sometimes|max:50',
            'day_prior' => 'sometimes|max:50',
            'carry_forward_amount' => 'sometimes|max:50',
            'carry_forward_expiry_amount' => 'sometimes|max:50',
            'carry_forward_expiry_period' => 'sometimes|nullable|enum_value:' . LeavePeriodType::class,
            'credit_deduction' => 'required|boolean',
            'credit_expiry_amount' => 'sometimes|max:50',
            'credit_expiry_period' => 'sometimes|nullable|enum_value:' . LeavePeriodType::class,
            'status' => 'sometimes|max:50',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }
}
