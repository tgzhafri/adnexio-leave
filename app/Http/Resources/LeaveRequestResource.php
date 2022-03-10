<?php

namespace App\Http\Resources;

use App\Models\LeaveCarryForward;
use App\Models\LeaveEntitlement;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = LeaveDate::where('leave_request_id', $this->id)->value('type');
        if ($type == 'full_day') {
            $duration = LeaveDate::where('leave_request_id', $this->id)->count();
        } else {
            $duration = 0.5;
        }

        $startDate = LeaveDate::where('leave_request_id', $this->id)->orderBy('date', 'asc')->value('date');
        $endDate = LeaveDate::where('leave_request_id', $this->id)->orderBy('date', 'desc')->value('date');

        $leavePolicy = LeavePolicy::where('id', $this->leave_policy_id)->first();

        $empEntitlement = LeaveEntitlement::where([
            ['staff_id', '=', $this->staff_id],
            ['leave_policy_id', '=', $this->leave_policy_id]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where([
                ['type', 1],
            ]);
        })->first();

        $creditEntitlement = LeaveEntitlement::where([
            ['staff_id', '=', $this->staff_id],
        ])->whereHas('leavePolicy', function ($query) {
            $query->where([
                ['type', 2],
            ]);
        })->first();
        // dd($creditEntitlement);
        $carryForward = null;

        // //*--------- ENTITLEMENT - for leave policy WITH entitlement ---------------// //
        if ($empEntitlement) {
            $entitlement = [
                'amount' => $empEntitlement->amount,
                'balance' => $empEntitlement->balance,
            ];

            // //*--------- ENTITLEMENT - for leave policy WITH entitlement WITH CREDIT DEDUCTION ---------------// //
            if ($leavePolicy->credit_deduction == 1) {
                $leaveCredit = [
                    'amount'  => $creditEntitlement->amount,
                    'balance'  => $creditEntitlement->balance,
                ];
            } else {
                $leaveCredit = null;
            }

            if (
                $leavePolicy->carry_forward_amount != null
                && $empEntitlement != null
            ) {
                $carryForwardEntitlement = LeaveCarryForward::where('entitlement_id', $empEntitlement->id)->first();
                if ($carryForwardEntitlement != null) {
                    $carryForward = [
                        'amount' => $carryForwardEntitlement->amount,
                        'balance' => $carryForwardEntitlement->balance,
                    ];
                }
            } else {
                $carryForward = null;
            }
        } else {
            // //*--------- LEAVE CREDIT & WITHOUT ENTITLEMENT LEAVE REQUEST ---------------// //
            $leaveCredit = [
                'outstanding' => $duration,
            ];
            $entitlement = null;
        }

        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'leave_policy_id' => $this->leave_policy_id,
            'leave_policy_name' => $leavePolicy->name,
            'status' => $this->status,
            'reason' => $this->reason,
            'documentation' => $this->documentation,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $type,
            'duration' => $duration,
            'leave_credit' => $leaveCredit,
            'entitlement' => $entitlement,
            'carry_forward' => $carryForward
        ];
    }
}
