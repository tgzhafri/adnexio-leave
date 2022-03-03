<?php

namespace App\Services;

use App\Http\Resources\EntitlementResource;
use App\Models\Entitlement;
use Carbon\Carbon;

class EntitlementService
{
    public function show($id)
    {
        $currentCycle = Carbon::now()->format('Y-m-d');

        $withEntitlements = Entitlement::where([
            ['employee_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where('with_entitlement', '=', 1);
        })->get();

        $withoutEntitlements = Entitlement::where([
            ['employee_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where('with_entitlement', '=', 0);
        })->get();


        $result = [
            'with_entitlement' => EntitlementResource::collection($withEntitlements),
            'without_entitlement' => EntitlementResource::collection($withoutEntitlements),
        ];
        return $result;
    }
}
