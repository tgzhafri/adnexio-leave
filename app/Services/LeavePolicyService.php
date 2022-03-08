<?php

namespace App\Services;

use App\Http\Requests\LeavePolicy\LeavePolicyPostRequest;
use App\Models\Staff;
use App\Models\Entitlement;
use App\Models\LeaveCategory;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;

class LeavePolicyService
{
    public function store(LeavePolicyPostRequest $request)
    {
        $staffs = Staff::all();
        $leavePolicy = LeavePolicy::create($request->validated());
        // ---------- start code for date manipulation ---------------- //
        $months = $this->cyclePeriod()['months'];
        $startYear = $this->cyclePeriod()['startYear'];
        $endYear = $this->cyclePeriod()['endYear'];
        $currentDate = $this->cyclePeriod()['currentDate'];
        // ---------- end code for date manipulation ---------------- //

        $entitlements = $request->entitlement; // insert into leave entitlement table
        if (!$entitlements) {
            // case for without entitlement leave policies,
            // insert into Entitlement table with NULL balance and amount for the said leave policy
            foreach ($staffs as $staff) {
                $this->withoutEntitlement($leavePolicy, $staff, $startYear, $endYear);
            }
        } else {
            foreach ($entitlements as $item) {
                $this->createLeaveEntitlement($item, $leavePolicy);
            }
        }
        $categoryArr = $request->category; // insert into category table
        if ($categoryArr) {
            foreach ($categoryArr as $item) {
                $this->createCategory($item, $leavePolicy);
            }
        }

        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = LeaveCategory::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('name')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);

        // //------ code start ---- Insert staff entitlement according to the requirement setup ----------////
        if ($leavePolicy->cycle_period == 'yearly') {
            $this->yearly($staffs, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear);
        }

        if ($leavePolicy->cycle_period == 'monthly') {
            $this->monthly($staffs, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy);
        }
        // //------ code end ---- Insert staff entitlement according to the requirement setup ----------////

        // to return every related models to leave policy
        return $this->leavePolicyResponse($leavePolicy);
    }

    public function update(LeavePolicy $leavePolicy, LeavePolicyPostRequest $request)
    {
        $staffs = Staff::all();
        $leavePolicy->update($request->validated());
        $getEntitlements = Entitlement::where('leave_policy_id', $leavePolicy->id)->first();

        // ---------- start code for date manipulation ---------------- //
        $months = $this->cyclePeriod()['months'];
        $startYear = $this->cyclePeriod()['startYear'];
        $endYear = $this->cyclePeriod()['endYear'];
        $currentDate = $this->cyclePeriod()['currentDate'];
        // ---------- end code for date manipulation ---------------- //

        // ------------ remove existing entitlement data and create new updated data -----------//
        if ($getEntitlements) {
            Entitlement::where('leave_policy_id', $leavePolicy->id)->forceDelete();
        }
        // ------------ remove existing entitlement data and create new updated data -----------//

        $entitlements = $request->entitlement;
        if (!$entitlements) {
            foreach ($staffs as $staff) {
                $this->withoutEntitlement($leavePolicy, $staff, $startYear, $endYear);
            }
        } else {
            foreach ($entitlements as $item) {
                if (isset($item['id'])) {
                    $this->updateLeaveEntitlement($item);
                } else {
                    $this->createLeaveEntitlement($item, $leavePolicy);
                }
            }
        }
        // method to insert multiple/array of requests into db of related model
        $categoryArr = $request->category;
        if ($categoryArr) {
            foreach ($categoryArr as $item) {
                // check if item existed, just update the new data
                if (isset($item['id'])) {
                    $this->updateCategory($item);
                } else { // check if item not exist, create and store new data
                    $this->createCategory($item, $leavePolicy);
                }
            }
        }
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = LeaveCategory::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('name')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);

        // //------ code start ---- Insert staff entitlement according to the requirement setup ----------////
        if ($leavePolicy->cycle_period == 'yearly') { //------ cycle period YEARLY ----------//
            $this->yearly($staffs, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear);
        }

        if ($leavePolicy->cycle_period == 'monthly') { // -------- cycle period MONTHLY ----------- //
            $this->monthly($staffs, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy);
        }
        // //------ code end ---- Insert staff entitlement according to the requirement setup ----------////

        // return leave policy including all related models
        return $this->leavePolicyResponse($leavePolicy);
    }

    public function cyclePeriod()
    {
        $startYear = Carbon::now()->startOfYear();
        $endYear = Carbon::now()->endOfYear();
        $currentDate = Carbon::now();
        ////------- method to list all 12 months in a year using carbon-----------//
        $period = CarbonPeriod::create($startYear, '1 month', $endYear);
        foreach ($period as $month) {
            $months[] = [
                "start_date" => Carbon::parse($month)->format('Y-m-d'),
                "end_date" => Carbon::parse($month)->endOfMonth()->format('Y-m-d'),
            ];
        }
        $data = array(
            'currentDate' => $currentDate,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'months' => $months
        );
        ////------- method to list all 12 months in a year using carbon-----------//
        return $data;
    }

    public function updateCategory($item)
    {
        LeaveCategory::where('id', $item['id'])
            ->update([
                'name' => $item['name'],
                'status' => $item['status'],
            ]);
    }

    public function createCategory($item, $leavePolicy)
    {
        $arr = [
            'leave_policy_id' => $leavePolicy->id,
            'name' => $item['name'],
            'status' => $item['status']
        ];
        LeaveCategory::create($arr);
    }

    public function updateLeaveEntitlement($item)
    {
        LeaveEntitlement::where('id', $item['id'])
            ->update([
                'layer' => $item['layer'],
                'amount' => $item['amount'],
                'start_year_of_service' => $item['start_year_of_service'],
                'end_year_of_service' => $item['end_year_of_service'],
            ]);
    }

    public function createLeaveEntitlement($item, $leavePolicy)
    {
        $arr = [
            'leave_policy_id' => $leavePolicy->id,
            'layer' => $item['layer'],
            'amount' => $item['amount'],
            'start_year_of_service' => $item['start_year_of_service'],
            'end_year_of_service' => $item['end_year_of_service'],
        ];
        LeaveEntitlement::create($arr);
    }

    public function withoutEntitlement($leavePolicy, $staff, $startYear, $endYear)
    {
        $arr = [
            'leave_policy_id' => $leavePolicy->id,
            'staff_id' => $staff['id'],
            'cycle_start_date' => $startYear,
            'cycle_end_date' => $endYear,
        ];
        Entitlement::create($arr);
    }

    public function yearly($staffs, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear)
    {
        foreach ($staffs as $staff) {
            $joined_date = Carbon::createFromFormat('Y-m-d', $staff['joined_date']);
            $year_of_service = $currentDate->diffInYears($joined_date);
            foreach ($leaveEntitlements as $leaveEntitlement) {
                if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                    if (in_array($staff['gender'], $categoriesArr) && in_array($staff['marital_status'], $categoriesArr) && in_array($staff['employment_type'], $categoriesArr)) {
                        $arr = [
                            'leave_policy_id' => $leavePolicy->id,
                            'staff_id' => $staff['id'],
                            'cycle_start_date' => $startYear,
                            'cycle_end_date' => $endYear,
                            'amount' => $leaveEntitlement['amount'],
                            'balance' => $leaveEntitlement['amount']
                        ];
                        Entitlement::create($arr);
                    }
                }
            }
        }
    }

    public function monthly($staffs, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy)
    {
        foreach ($staffs as $staff) {
            foreach ($months as $month) {
                $joined_date = Carbon::createFromFormat('Y-m-d', $staff['joined_date']);
                $year_of_service = $currentDate->diffInYears($joined_date);
                foreach ($leaveEntitlements as $leaveEntitlement) {
                    if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                        if (in_array($staff['gender'], $categoriesArr) && in_array($staff['marital_status'], $categoriesArr) && in_array($staff['employment_type'], $categoriesArr)) {
                            $arr = [
                                'leave_policy_id' => $leavePolicy->id,
                                'staff_id' => $staff['id'],
                                'cycle_start_date' => $month['start_date'],
                                'cycle_end_date' => $month['end_date'],
                                'amount' => $leaveEntitlement['amount'],
                                'balance' => $leaveEntitlement['amount']
                            ];
                            Entitlement::create($arr);
                        }
                    }
                }
            }
        }
    }

    public function leavePolicyResponse($leavePolicy)
    {
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'leaveCategory'])
            ->get();

        return $detailPolicy;
    }
};
