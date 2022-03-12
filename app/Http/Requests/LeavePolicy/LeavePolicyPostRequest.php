<?php

namespace App\Http\Requests\LeavePolicy;

use App\Enums\AccrualType;
use App\Enums\LeavePeriodType;
use App\Enums\LeavePolicyType;
use App\Enums\QuotaType;
use App\Models\LeaveEntitlement;
use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'company_id' => 'required|max:50',
            // 'approval_route_id' => 'required|max:50',
            'name' => 'required|max:50',
            'abbreviation' => 'required|max:10',
            'description' => 'required|max:125',
            'color' => 'sometimes|max:50',
            'type' => 'required|enum_value:' . LeavePolicyType::class,
            'attachment_required' => 'required|boolean',
            'reason_required' => 'required|boolean',
            'half_day_option' => 'required|boolean',
            'cycle_period' => 'required|enum_value' . LeavePeriodType::class,
            'eligible_amount' => 'sometimes|max:50',
            'eligible_period' => 'sometimes|enum_value:' . LeavePeriodType::class,
            'accrual_type' => 'required|enum_value:' . AccrualType::class,
            'accrual_happen' => 'sometimes|enum_value:' . LeavePeriodType::class,
            'quota_amount' => 'sometimes|max:50',
            'quota_unit' => 'sometimes|enum_value:' . QuotaType::class,
            'quota_category' => 'sometimes|enum_value' . QuotaType::class,
            'restriction_amount' => 'sometimes|max:50',
            'day_prior' => 'sometimes|max:50',
            'carry_forward_amount' => 'sometimes|max:50',
            'carry_forward_expiry_amount' => 'sometimes|max:50',
            'carry_forward_expiry_period' => 'sometimes|enum_value:' . LeavePeriodType::class,
            'credit_deduction' => 'sometimes|boolean',
            'credit_expiry_amount' => 'sometimes|max:50',
            'credit_expiry_period' => 'sometimes|enum_value:' . LeavePeriodType::class,
            'status' => 'sometimes|max:50',
            // 'layer' => 'required',
            // 'amount' => 'required',
            // 'start_year_of_service' => 'required',
            // 'end_year_of_service' => 'required'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // 'username'            => trans('userpasschange.username'),
            // 'oldpassword'             => trans('userpasschange.oldpassword'),
            // 'newpassword'             => trans('userpasschange.newpassword'),
            // 'newpasswordagain'       => trans('userpasschange.newpasswordagain'),
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        // use trans instead on Lang
        return [
            //     'username.required' => Lang::get('userpasschange.usernamerequired'),
            //     'oldpassword.required' => Lang::get('userpasschange.oldpasswordrequired'),
            //     'oldpassword.max' => Lang::get('userpasschange.oldpasswordmax255'),
            //     'newpassword.required' => Lang::get('userpasschange.newpasswordrequired'),
            //     'newpassword.min' => Lang::get('userpasschange.newpasswordmin6'),
            //     'newpassword.max' => Lang::get('userpasschange.newpasswordmax255'),
            //     'newpassword.alpha_num' => Lang::get('userpasschange.newpasswordalpha_num'),
            //     'newpasswordagain.required' => Lang::get('userpasschange.newpasswordagainrequired'),
            //     'newpasswordagain.same:newpassword' => Lang::get('userpasschange.newpasswordagainsamenewpassword'),
            //     'username.max' => 'The :attribute field must  have under 255 chars',
        ];
    }
}
