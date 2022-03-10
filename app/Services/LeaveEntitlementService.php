<?php

namespace App\Services;

use App\Http\Resources\LeaveEntitlementResource;
use App\Models\LeaveEntitlement;
use Carbon\Carbon;

class LeaveEntitlementService
{
    public function show($id)
    {
        $currentCycle = Carbon::now()->format('Y-m-d');

        $withEntitlements = LeaveEntitlement::where([
            ['staff_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where('type', 1);
        })->get();

        $withoutEntitlements = LeaveEntitlement::where([
            ['staff_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where([
                ['type', 0],
            ]);
        })->get();

        $leaveCredit = LeaveEntitlement::where([
            ['staff_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where('type', 2);
        })->get();

        $result = [
            'with_entitlement' => LeaveEntitlementResource::collection($withEntitlements),
            'without_entitlement' => LeaveEntitlementResource::collection($withoutEntitlements),
            'leave_credit' => LeaveEntitlementResource::collection($leaveCredit),
        ];
        return $result;
    }
}
