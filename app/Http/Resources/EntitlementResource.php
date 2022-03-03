<?php

namespace App\Http\Resources;

use App\Models\Entitlement;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EntitlementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        $leavePolicy = LeavePolicy::where('id', $this->leave_policy_id)->first();
        $leaveRequests = LeaveRequest::where([
            ['employee_id', '=', $this->employee_id],
            ['leave_policy_id', '=', $this->leave_policy_id]
        ])->get();

        $utilised = 0;
        $outstanding = 0;

        foreach ($leaveRequests as $item) {
            $leaveDate = LeaveDate::where('leave_request_id', $item['id'])->select('date')->first();

            $leaveDate->date < $currentDate ? $utilised++ : $outstanding++;
        }
        // dd($utilised, $outstanding);

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'cycle_start_date' => $this->cycle_start_date,
            'cycle_end_date' => $this->cycle_end_date,
            'entitlement' => [
                'amount' => $this->amount,
                'balance' => $this->balance,
                'utilised' => $utilised,
                'outstanding' => $outstanding
            ],
            'leave_policy_id' => $this->leave_policy_id,
            'leave_policy' => [
                'id' => $leavePolicy->id,
                'name' => $leavePolicy->name,
                'color' => $leavePolicy->color,
                'abbreviation' => $leavePolicy->abbreviation,
                'description' => $leavePolicy->description,
                'with_entitlement' => $leavePolicy->with_entitlement,
                'document_required' => $leavePolicy->document_required,
                'reason_required' => $leavePolicy->reason_required,
                'half_day_option' => $leavePolicy->half_day_option,
                'credit_deduction' => $leavePolicy->credit_deduction,
                'credit_expiry_amount' => $leavePolicy->credit_expiry_amount,
                'credit_expiry_period' => $leavePolicy->credit_expiry_period,
                'cycle_period' => $leavePolicy->cycle_period,
                'eligible_amount' => $leavePolicy->eligible_amount,
                'eligible_period' => $leavePolicy->eligible_period,
                'accrual_option' => $leavePolicy->accrual_option,
                'accrual_happen' => $leavePolicy->accrual_happen,
                'approval_route_id' => $leavePolicy->approval_route_id,
                'leave_quota_amount' => $leavePolicy->leave_quota_amount,
                'leave_quota_unit' => $leavePolicy->leave_quota_unit,
                'leave_quota_category' => $leavePolicy->leave_quota_category,
                'restriction_amount' => $leavePolicy->restriction_amount,
                'day_prior' => $leavePolicy->day_prior,
                'carry_forward_amount' => $leavePolicy->carry_forward_amount,
                'carry_forward_expiry' => $leavePolicy->carry_forward_expiry,
                'status' => $leavePolicy->status,
            ],
        ];
    }
}
