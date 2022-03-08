<?php

namespace App\Http\Resources;

use App\Models\Entitlement;
use App\Models\LeaveCredit;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPSTORM_META\map;

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
        $utilised = null;
        $outstanding = null;
        $requested = null;
        $granted = null;
        $rejected = null;
        $creditUtilised = null;
        $creditOutstanding = null;
        $leaveCredit = null;
        $entitlement = null;

        $replacementLeavePolicy = LeavePolicy::where('id', 6)->first();
        $leavePolicy = LeavePolicy::where('id', $this->leave_policy_id)->first();

        $leaveRequests = LeaveRequest::where([
            ['staff_id', '=', $this->staff_id],
            ['leave_policy_id', '=', $this->leave_policy_id]
        ])->get()->except('leave_policy_id', 6);

        if ($leavePolicy['type'] === 1) {
            foreach ($leaveRequests as $item) {
                $leaveDates = LeaveDate::where('leave_request_id', $item['id'])->select('date')->get();

                foreach ($leaveDates as $leaveDate) {
                    $leaveDate->date < $currentDate ? $utilised++ : $outstanding++;
                }
            }
        }

        // // ----- to show WITHOUT ENTITLEMENT leave policy ---------- // //
        if ($leavePolicy['type'] !== 1) {
            $empEntitlements = Entitlement::where([
                ['staff_id', '=', $this->staff_id],
                ['leave_policy_id', '=', $this->leave_policy_id]
            ])->get();

            foreach ($empEntitlements as $item) {
                $requested = LeaveCredit::where('entitlement_id', $item['id'])->sum('requested');
            }

            foreach ($leaveRequests as $item) {
                $leaveDates = LeaveDate::where('leave_request_id', $item['id'])->select('date')->get();

                foreach ($leaveDates as $leaveDate) {
                    $leaveDate->date < $currentDate ? $creditUtilised++ : $creditOutstanding++;
                }
            }

            $entitlement = null;

            $leaveCredit = [
                'requested' => $requested,
                'granted' => $granted,
                'rejected' => $rejected,
                'utilised' => $creditUtilised,
                'outstanding' => $creditOutstanding
            ];
        } else {
            $entitlement = [
                'amount' => $this->amount,
                'balance' => $this->balance,
                'utilised' => $utilised,
                'outstanding' => $outstanding
            ];

            $leaveCredit = null;
        }

        if ($leavePolicy['credit_deduction'] === 1) {
            $entitlements = Entitlement::where([
                ['staff_id', '=', $this->staff_id],
                ['leave_policy_id', '=', $this->leave_policy_id]
            ])->get();

            foreach ($entitlements as $item) {
                $requested = LeaveCredit::where('entitlement_id', $item['id'])->sum('requested');
            }

            foreach ($leaveRequests as $item) {
                $leaveDates = LeaveDate::where('leave_request_id', $item['id'])->select('date')->get();

                foreach ($leaveDates as $leaveDate) {
                    $leaveDate->date < $currentDate ? $creditUtilised++ : $creditOutstanding++;
                }
            }

            $leaveCredit = [
                'requested' => $requested,
                'granted' => $granted,
                'rejected' => $rejected,
                'utilised' => $creditUtilised,
                'outstanding' => $creditOutstanding
            ];
        }

        // // ----- To show replacement CREDIT applicable to which leave policy -------- // //
        $applicableLeave = null;
        if ($replacementLeavePolicy->status === 1 && $leavePolicy->id === 6) {
            $leavePolicies = LeavePolicy::where('credit_deduction', 1)->get();

            foreach ($leavePolicies as $item) {
                $applicableLeave[] = [
                    'leave_policy_id' => $item['id'],
                    'leave_policy_name' => $item['name']
                ];
            }
        }

        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'cycle_start_date' => $this->cycle_start_date,
            'cycle_end_date' => $this->cycle_end_date,
            'entitlement' => $entitlement,
            'leave_credit' => $leaveCredit,
            'applicable_leave' => $applicableLeave,
            'leave_policy_id' => $this->leave_policy_id,
            'leave_policy' => [
                'id' => $leavePolicy->id,
                'name' => $leavePolicy->name,
                'color' => $leavePolicy->color,
                'abbreviation' => $leavePolicy->abbreviation,
                'description' => $leavePolicy->description,
                'type' => $leavePolicy->type,
                'attachment_required' => $leavePolicy->attachment_required,
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
                'quota_amount' => $leavePolicy->quota_amount,
                'quota_unit' => $leavePolicy->quota_unit,
                'quota_category' => $leavePolicy->quota_category,
                'restriction_amount' => $leavePolicy->restriction_amount,
                'day_prior' => $leavePolicy->day_prior,
                'carry_forward_amount' => $leavePolicy->carry_forward_amount,
                'carry_forward_expiry' => $leavePolicy->carry_forward_expiry,
                'status' => $leavePolicy->status,
            ],
        ];
    }
}
