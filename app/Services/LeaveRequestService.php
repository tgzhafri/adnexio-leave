<?php

namespace App\Services;

use App\Http\Resources\LeaveRequestResource;
use App\Models\Entitlement;
use App\Models\LeaveCredit;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use Illuminate\Support\Arr;

class LeaveRequestService
{
    public function store($request, $approval)
    {
        // $path = $request->file('documentation')->store('images', 's3');
        // $url = 'https://myawsfirsttrial.s3.ap-southeast-1.amazonaws.com/';
        // $link = $url . $path;

        $withEntitlement = LeavePolicy::where('with_entitlement', 1)->select('id')->get()->toArray();
        $withoutEntitlement = LeavePolicy::where('with_entitlement', 0)->select('id')->get()->toArray();

        // dd($withEntitlement, $withoutEntitlement);
        $withEntitlementArr = Arr::flatten($withEntitlement);
        $withoutEntitlementArr = Arr::flatten($withoutEntitlement);

        $leaveRequest = LeaveRequest::create($request->all());
        $leaveDate = $request->date;
        $duration = 0;
        foreach ($leaveDate as $item) {
            $arr = [
                'leave_request_id' => $leaveRequest->id,
                'date' => $item['date'],
                'type' => $item['type']
            ];
            LeaveDate::create($arr);
            $item['type'] == 'full_day' ? $duration++ : $duration = 0.5;
        }
        // // method to insert single request into db of related model
        $approval->fill([
            'leave_request_id' => $leaveRequest->id,
            'verifier_id' => $leaveRequest->staff_id,
            'status' => 1,
        ]);
        $leaveRequest->approval()->save($approval);

        $entitlement = Entitlement::where([
            ['staff_id', '=', $request->staff_id],
            ['leave_policy_id', '=', $request->leave_policy_id]
        ])->first();

        // // ------- leave request for leave policy WITH entitlement ------------ // //
        if (in_array($request->leave_policy_id, $withEntitlementArr)) {

            // //--------- update leave balance for staff entitlement ----------// //
            $updatedBalance = $entitlement->balance - $duration;

            Entitlement::where([
                ['staff_id', '=', $request->staff_id],
                ['leave_policy_id', '=', $request->leave_policy_id]
            ])->update([
                'balance' => $updatedBalance
            ]);
        }

        // // ------- leave request for leave policy WITHOUT entitlement ------------ // //
        if (in_array($request->leave_policy_id, $withoutEntitlementArr)) {
            $arr = [
                'entitlement_id' => $entitlement->id,
                'requested' => $duration,
                'expiry_date' => '2022-12-31',
                'status' => 1
            ];
            LeaveCredit::create($arr);

            return $arr;
        }
        // to return every related models to leave request
        $detailRequest = LeaveRequest::whereId($leaveRequest->id)->get();

        $result = LeaveRequestResource::collection($detailRequest);

        return $result;
    }

    public function show($id)
    {
    }
}
