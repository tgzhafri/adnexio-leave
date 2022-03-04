<?php

namespace App\Http\Resources;

use App\Models\Entitlement;
use App\Models\LeaveDate;
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

        $entitlement = Entitlement::where([
            ['staff_id', '=', $this->staff_id],
            ['leave_policy_id', '=', $this->leave_policy_id]
        ])->first();

        $entitlementAmount = $entitlement->amount;
        $balance = $entitlement->balance;

        return [
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'leave_policy_id' => $this->leave_policy_id,
            'status' => $this->status,
            'reason' => $this->reason,
            'documentation' => $this->documentation,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $type,
            'duration' => $duration,
            'entitlement' => [
                'amount' => $entitlementAmount,
                'balance' => $balance
            ],
        ];
    }
}
