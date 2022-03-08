<?php

namespace App\Services;

use App\Http\Resources\LeaveRequestResource;
use App\Models\Entitlement;
use App\Models\LeaveCredit;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class LeaveRequestService
{
    public function store($request, $approval)
    {
        // $path = $request->file('attachment')->store('images', 's3');
        // $url = 'https://myawsfirsttrial.s3.ap-southeast-1.amazonaws.com/';
        // $link = $url . $path;

        $leavePolicy = LeavePolicy::whereId($request->leave_policy_id)->first();
        $staffTotal = Staff::all()->count();
        $withEntitlement = LeavePolicy::where('type', 1)->select('id')->get()->toArray();
        $withoutEntitlement = LeavePolicy::where('type', 0)->select('id')->get()->toArray();
        $leaveCredit = LeavePolicy::where('type', 2)->select('id')->first();
        $currentDate = Carbon::now()->startOfDay();

        //* sort leave date request in ascending date order *//
        $dates = $request->date;
        $dates = array_values(Arr::sort($dates, function ($value) {
            return new Carbon($value['date']);
        }));
        $duration = 0;

        $withEntitlementArr = Arr::flatten($withEntitlement);
        $withoutEntitlementArr = Arr::flatten($withoutEntitlement);

        if ($leavePolicy->attachment_required === 1 && $request->attachment == null) {
            $error = [
                'code' => 400,
                'message' => 'Attachment is required!',
                'data' => null
            ];
            return $error;
        }
        if ($leavePolicy->reason_required === 1 && $request->reason == null) {
            $error = [
                'code' => 400,
                'message' => 'Reason is required!',
                'data' => null
            ];
            return $error;
        }

        if ($leavePolicy->eligible_amount !== null && $leavePolicy->eligible_period !== null) {
            $staff = Staff::whereId($request->staff_id)->first();
            $joinedDate = Carbon::createFromDate($staff->joined_date);
            $eligibleDate = $joinedDate->add($leavePolicy->eligible_amount, $leavePolicy->eligible_period);
            $duration = $eligibleDate->startOfDay()->diffInDays($currentDate);

            if ($eligibleDate > $currentDate) {
                $error = [
                    'code' => 400,
                    'message' => 'Employee does not meet the minimum eligible period requirement.' . " " . $duration . " " . 'days left to fulfill the requirement',
                    'data' => $duration,
                ];
                return $error;
            }
        }

        foreach ($dates as $item) {

            $count = LeaveDate::where('date', $item['date'])
                ->whereHas('leaveRequest', function ($query) use ($leavePolicy) {
                    $query->where([
                        ['leave_policy_id', $leavePolicy->id],
                    ]);
                })->count();

            if ($item['type'] !== 'full_day' && $leavePolicy->half_day_option === 1 && count($dates) > 1) {
                $error = [
                    'code' => 400,
                    'message' => 'Half day leave request can only apply one date at a time',
                    'data' => null
                ];
                return $error;
            }

            //* TODO: logic condition for department, quota_unit NUMBER & PERCENT
            if ($leavePolicy->quota_amount !== null && $leavePolicy->quota_unit !== null && $leavePolicy->quota_category) {

                if ($leavePolicy->quota_unit == 'number' && $leavePolicy->quota_category == 'company') {
                    $quota = $leavePolicy->quota_amount;
                }

                if ($leavePolicy->quota_unit == 'percent' && $leavePolicy->quota_category == 'company') {
                    $quota = round($leavePolicy->quota_amount / 100 * $staffTotal);
                }

                $slot = $quota - $count;
                if ($slot <= 0) {
                    $error = [
                        'code' => 400,
                        'message' => 'No available leave request slot for the selected date -' . " " . $item['date'],
                        'data' => $item['date']
                    ];
                    return $error;
                }
            }
        }
        // //* restriction amount: to restrict number of consecutive days leave request can be applied */
        if ($leavePolicy->restriction_amount !== null) {
            $restrictionAmount = $leavePolicy->restriction_amount;

            if (count($dates) > $restrictionAmount) {
                $error = [
                    'code' => 400,
                    'message' => 'Number of days selected for the leave request cannot exceed ' . $restrictionAmount . " days",
                    'data' => $restrictionAmount
                ];
                return $error;
            }
        }
        // //* day prior: to check number of day prior to leave request can be made */ //
        if ($leavePolicy->day_prior !== null) {
            $dayPrior = $leavePolicy->day_prior;
            $startDate = Carbon::createFromDate($dates[0]['date']);
            $duration = $currentDate->startOfDay()->diffInDays($startDate);

            if ($duration < $dayPrior && $startDate >= $currentDate) {
                $error = [
                    'code' => 400,
                    'message' => 'Leave request needs to be apply ' . $dayPrior . " days in advance",
                    'data' => $dayPrior
                ];
                return $error;
            }
        }

        //* to create leave request inside database *//
        $leaveRequest = LeaveRequest::create($request->all());

        foreach ($dates as $item) {
            $arr = [
                'leave_request_id' => $leaveRequest->id,
                'date' => $item['date'],
                'type' => $item['type']
            ];
            LeaveDate::create($arr);
            $item['type'] == 'full_day' ? $duration++ : $duration = 0.5;
        }

        // //* method to insert single request into db of related model */ //
        $approval->fill([
            'leave_request_id' => $leaveRequest->id,
            'verifier_id' => $leaveRequest->staff_id,
            'status' => 1,
        ]);
        $leaveRequest->leaveApproval()->save($approval);

        $entitlement = Entitlement::where([
            ['staff_id', '=', $request->staff_id],
            ['leave_policy_id', '=', $request->leave_policy_id]
        ])->first();

        // //* ------- leave request for leave policy WITH entitlement ------------ */ //
        if (in_array($request->leave_policy_id, $withEntitlementArr)) {

            // TODO: compare LEAVE CREDIT vs CARRY FORWARD expiry date, to utilise the one expiring first

            //* check if the requested leave policy is applicable for credit deduction option *//
            if ($leavePolicy->credit_deduction == 1) {
                $leaveCredit = LeaveCredit::where('entitlement_id', $entitlement->id)->get();

                dd($leaveCredit);
            }


            // //*--------- update leave balance for staff entitlement ----------*/ //
            $updatedBalance = $entitlement->balance - $duration;

            Entitlement::where([
                ['staff_id', '=', $request->staff_id],
                ['leave_policy_id', '=', $request->leave_policy_id]
            ])->update([
                'balance' => $updatedBalance
            ]);
        }

        // //* ------- leave request for leave policy WITHOUT entitlement ------------ */ //
        if (in_array($request->leave_policy_id, $withoutEntitlementArr)) {
            $arr = [
                'entitlement_id' => $entitlement->id,
                'leave_request_id' => $leaveRequest->id,
                'requested' => $duration,
                'status' => 1
            ];
            LeaveCredit::create($arr);
        }

        // //*------- leave request for leave policy LEAVE CREDIT ------------ */ //
        if ($request->leave_policy_id === $leaveCredit->id) {
            $arr = [
                'entitlement_id' => $entitlement->id,
                'leave_request_id' => $leaveRequest->id,
                'requested' => $duration,
                'status' => 1
            ];
            LeaveCredit::create($arr);
        }

        //* to return every related models to leave request //
        $detailRequest = LeaveRequest::whereId($leaveRequest->id)->get();

        $result = LeaveRequestResource::collection($detailRequest);

        $success = [
            'code' => 200,
            'message' => 'Leave request successfully submitted!',
            'data' => $result
        ];
        return $success;
    }

    public function show($id)
    {
    }
}
