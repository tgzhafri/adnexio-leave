<?php

namespace App\Services;

use App\Enums\AccrualType;
use App\Enums\LeaveDayType;
use App\Enums\LeavePolicyType;
use App\Enums\QuotaType;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveCarryForward;
use App\Models\LeaveEntitlement;
use App\Models\LeaveCredit;
use App\Models\LeaveDate;
use App\Models\LeavePolicy;
use App\Models\LeaveRequest;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use League\CommonMark\Extension\SmartPunct\Quote;

class LeaveRequestService
{
    public function store($request, $approval)
    {
        // $path = $request->file('attachment')->store('images', 's3');
        // $url = 'https://myawsfirsttrial.s3.ap-southeast-1.amazonaws.com/';
        // $link = $url . $path;

        $leavePolicy = LeavePolicy::whereId($request->leave_policy_id)->first();
        $staffTotal = Staff::all()->count();
        $withEntitlementPolicy = LeavePolicy::where('type', LeavePolicyType::WithEntitlement)->select('id')->get()->toArray();
        $withoutEntitlementPolicy = LeavePolicy::where('type', LeavePolicyType::WithoutEntitlement)->select('id')->get()->toArray();
        $leaveCreditPolicy = LeavePolicy::where('type', LeavePolicyType::LeaveCredit)->select('id')->first();
        $currentDate = Carbon::now()->startOfDay();
        $lastYear = Carbon::now()->startOfYear()->subYear()->format('Y');

        //* sort leave date request in ascending date order *//
        $dates = $request->date;
        $dates = array_values(Arr::sort($dates, function ($value) {
            return new Carbon($value['date']);
        }));
        $withEntitlementArr = Arr::flatten($withEntitlementPolicy);
        $withoutEntitlementArr = Arr::flatten($withoutEntitlementPolicy);

        $leaveEntitlement = LeaveEntitlement::where([
            ['staff_id', '=', $request->staff_id],
            ['leave_policy_id', '=', $request->leave_policy_id]
        ])->first();

        $creditEntitlement = LeaveEntitlement::where([
            ['staff_id', $request->staff_id]
        ])->whereHas('leavePolicy', function ($query) {
            $query->where([
                ['type', LeavePolicyType::LeaveCredit],
            ]);
        })->first();

        $carryForward = LeaveCarryForward::where([
            ['entitlement_id', $leaveEntitlement->id],
            ['from_year', $lastYear]
        ])->first();

        if ($leaveEntitlement->balance < count($request->date)) {
            $error = [
                'code' => 400,
                'message' => 'Requested off days is more than leave balance available',
                'data' => null
            ];
            return $error;
        }
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
            $eligibleDuration = $eligibleDate->startOfDay()->diffInDays($currentDate);

            if ($eligibleDate > $currentDate) {
                $error = [
                    'code' => 400,
                    'message' => 'Employee does not meet the minimum eligible period requirement.' . " " . $eligibleDuration . " " . 'days left to fulfill the requirement',
                    'data' => $eligibleDuration,
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

            if ($item['type'] !== LeaveDayType::FullDay && $leavePolicy->half_day_option === 1 && count($dates) > 1) {
                $error = [
                    'code' => 400,
                    'message' => 'Half day leave request can only apply one date at a time',
                    'data' => null
                ];
                return $error;
            }

            //* TODO: logic condition for department, quota_unit NUMBER & PERCENT
            if ($leavePolicy->quota_amount !== null && $leavePolicy->quota_unit !== null && $leavePolicy->quota_category) {

                if ($leavePolicy->quota_unit == QuotaType::Number && $leavePolicy->quota_category == QuotaType::Company) {
                    $quota = $leavePolicy->quota_amount;
                }

                if ($leavePolicy->quota_unit == QuotaType::Percent && $leavePolicy->quota_category == QuotaType::Company) {
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
            $diffDay = $currentDate->startOfDay()->diffInDays($startDate);

            if ($diffDay < $dayPrior && $startDate >= $currentDate) {
                $error = [
                    'code' => 400,
                    'message' => 'Leave request needs to be apply ' . $dayPrior . " days in advance",
                    'data' => $dayPrior
                ];
                return $error;
            }
        }

        // //* method to insert single request into db of related model */ //
        // $approval->fill([
        //     'leave_request_id' => $leaveRequest->id,
        //     'verifier_id' => $leaveRequest->staff_id,
        //     'status' => 1,
        // ]);
        // $leaveRequest->leaveApproval()->save($approval);


        // //* ------- leave balance for leave policy WITH entitlement ------------ */ //
        if (in_array($request->leave_policy_id, $withEntitlementArr)) {

            //* to create leave request inside database *//
            //* check if employee has balance entitlement before storing the leave request WITH ENTITLEMENT

            if (
                $leaveEntitlement->balance
                || $creditEntitlement->balance
                || $carryForward->balance
            ) {
                $leaveRequest = LeaveRequest::create($request->all());
                $requestDuration = null;
                foreach ($dates as $item) {
                    $arr = [
                        'leave_request_id' => $leaveRequest->id,
                        'date' => $item['date'],
                        'type' => $item['type']
                    ];
                    LeaveDate::create($arr);
                    $item['type'] == LeaveDayType::FullDay ? $requestDuration += 1.0 : $requestDuration = 0.5;
                }
            } else {
                $error = [
                    'code' => 400,
                    'message' => 'Leave request unsuccessful as there are no more leave balance available',
                    'data' => null
                ];
                return $error;
            }

            //* leave utilization hierarchy
            //* 1. Leave Credit, 2. Carry Forward from previous year, 3. current year Entitlement

            // TODO: once leave credit granted/approved, update amount in ENTITLEMENT for LEAVE CREDIT policy
            //* check if the requested leave policy is applicable for credit deduction option
            //* or CARRY FORWARD leave balance to be utilize
            //* if none then proceed with deduction from entitlement balance
            if (
                $leavePolicy->credit_deduction == 1
                && $creditEntitlement->balance != 0
                || $creditEntitlement->balance != null
                || $leavePolicy->carry_forward_amount != null
                && $carryForward != null
            ) {
                //* check amount for each leave credit granted to be deducted first when applying leave *//
                if ($creditEntitlement->balance <= $requestDuration) {
                    LeaveEntitlement::where('id', $creditEntitlement->id)->update([
                        'balance' => 0
                    ]);
                    $balanceAfterCreditDeduction = $requestDuration - $creditEntitlement->balance;

                    if ($carryForward->balance <= $balanceAfterCreditDeduction) {
                        LeaveCarryForward::where('entitlement_id', $leaveEntitlement->id)->update([
                            'balance' => 0
                        ]);

                        // //*--------- update leave balance for staff entitlement ----------*/ //

                        if ($leavePolicy->accrual_type == AccrualType::FullAmount) {
                            $balanceAfterCarryForwardDeduction = $balanceAfterCreditDeduction - $carryForward->balance;
                            $updatedBalance = $leaveEntitlement->balance - $balanceAfterCarryForwardDeduction;

                            LeaveEntitlement::where('id', $leaveEntitlement->id)->update([
                                'balance' => $updatedBalance
                            ]);
                        }
                    } else {
                        $bal = $carryForward->balance - $balanceAfterCreditDeduction;
                        LeaveCarryForward::where('entitlement_id', $leaveEntitlement->id)->update([
                            'balance' => $bal
                        ]);
                    }
                } else {
                    $bal = $creditEntitlement->balance - $requestDuration;
                    LeaveEntitlement::where('id', $creditEntitlement->id)->update([
                        'balance' => $bal
                    ]);
                }
            } else {
                $balance = $leaveEntitlement->balance - $requestDuration;
                LeaveEntitlement::where('id', $leaveEntitlement->id)->update([
                    'balance' => $balance,
                ]);
            }
            $detailRequest = LeaveRequest::whereId($leaveRequest->id)->get();

            $result = LeaveRequestResource::collection($detailRequest);

            $success = [
                'code' => 200,
                'message' => 'Leave request successfully submitted!',
                'data' => $result
            ];
            return $success;
        }

        // //* ------- leave credit balance for leave policy WITHOUT entitlement ------------ */ //
        // //*------- && ------------ */ //
        // //*------- leave credit balance for leave policy LEAVE CREDIT ------------ */ //
        if (
            in_array($request->leave_policy_id, $withoutEntitlementArr) // leave policy WITHOUT entitlement
            || $request->leave_policy_id === $leaveCreditPolicy->id // leave credit request policy
        ) {
            $leaveRequest = LeaveRequest::create($request->all());
            $requestDuration = null;
            foreach ($dates as $item) {
                $arr = [
                    'leave_request_id' => $leaveRequest->id,
                    'date' => $item['date'],
                    'type' => $item['type']
                ];
                LeaveDate::create($arr);
                $item['type'] == LeaveDayType::FullDay ? $requestDuration += 1.0 : $requestDuration = 0.5;
            }

            $arr = [
                'entitlement_id' => $leaveEntitlement->id,
                'leave_request_id' => $leaveRequest->id,
                'requested' => $requestDuration,
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
