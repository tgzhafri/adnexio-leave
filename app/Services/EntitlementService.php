<?php

namespace App\Services;

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
        ])
            ->whereHas('leavePolicy', function ($query) {
                $query->where('with_entitlement', '=', 1);
            })
            ->with('leavePolicy', function ($query) {
                $query->select('id', 'name', 'color', 'abbreviation', 'description', 'with_entitlement');
            })
            ->get();

        $withoutEntitlements = Entitlement::where([
            ['employee_id', '=', $id],
            ['cycle_start_date', '<=', $currentCycle],
            ['cycle_end_date', '>=', $currentCycle]
        ])
            ->whereHas('leavePolicy', function ($query) {
                $query->where('with_entitlement', '=', 0);
            })
            ->with('leavePolicy', function ($query) {
                $query->select('id', 'name', 'color', 'abbreviation', 'description', 'with_entitlement');
            })
            ->get();

        // $replacement

        $result = [
            'withEntitlements' => $withEntitlements,
            'withoutEntitlements' => $withoutEntitlements
        ];

        return $result;
    }
}
