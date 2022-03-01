<?php

namespace App\Services;

use App\Http\Requests\LeavePolicy\StoreRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Entitlement;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;

class LeavePolicyService
{
    public function store(StoreRequest $request)
    {
        $leavePolicy = LeavePolicy::create($request->all());

        // ---------- start code for date manipulation ---------------- //
        $months = $this->cyclePeriod()['months'];
        $startYear = $this->cyclePeriod()['startYear'];
        $endYear = $this->cyclePeriod()['endYear'];
        $currentDate = $this->cyclePeriod()['currentDate'];
        // ---------- end code for date manipulation ---------------- //

        $employees = Employee::all();
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('name')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);

        $leaveEntitlement = $request->entitlement; // insert into leave entitlement table
        if (!$leaveEntitlement) {
            // case for without entitlement leave policies
            foreach ($employees as $employee) {
                $this->withoutEntitlement($leavePolicy, $employee, $startYear, $endYear);
            }
        } else {
            foreach ($leaveEntitlement as $item) {
                $this->createLeaveEntitlement($item, $leavePolicy);
            }
        }
        // method to insert multiple/array of requests into db of related model
        $categoryArr = $request->category; // update category table
        if ($categoryArr) {
            foreach ($categoryArr as $item) {
                $this->createCategory($item, $leavePolicy);
            }
        }

        // //------ code start ---- Insert employee entitlement according to the requirement setup ----------////
        if ($leavePolicy->cycle_period == 'yearly') {
            $this->yearly($employees, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear);
        }

        if ($leavePolicy->cycle_period == 'monthly') {
            $this->monthly($employees, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy);
        }
        // //------ code end ---- Insert employee entitlement according to the requirement setup ----------////

        // to return every related models to leave policy
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalRoute', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave policies stored successful',
            'leave_policy' => $detailPolicy
        ]);
    }

    public function update(LeavePolicy $leavePolicy, StoreRequest $request)
    {
        $leavePolicy->update($request->validated());

        $employees = Employee::all();
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('name')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);
        $entitlements = Entitlement::where('leave_policy_id', $leavePolicy->id)->first();

        // ---------- start code for date manipulation ---------------- //
        $months = $this->cyclePeriod()['months'];
        $startYear = $this->cyclePeriod()['startYear'];
        $endYear = $this->cyclePeriod()['endYear'];
        $currentDate = $this->cyclePeriod()['currentDate'];
        // ---------- end code for date manipulation ---------------- //

        // ------------ remove existing entitlement data and create new updated data -----------//
        if ($entitlements) {
            Entitlement::where('leave_policy_id', $leavePolicy->id)->forceDelete();
        }
        // ------------ remove existing entitlement data and create new updated data -----------//

        $entitlements = $request->entitlement;
        if (!$entitlements) {
            foreach ($employees as $employee) {
                $this->withoutEntitlement($leavePolicy, $employee, $startYear, $endYear);
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

        // //------ code start ---- Insert employee entitlement according to the requirement setup ----------////
        if ($leavePolicy->cycle_period == 'yearly') { //------ cycle period YEARLY ----------//
            $this->yearly($employees, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear);
        }

        if ($leavePolicy->cycle_period == 'monthly') { // -------- cycle period MONTHLY ----------- //
            $this->monthly($employees, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy);
        }
        // //------ code end ---- Insert employee entitlement according to the requirement setup ----------////

        // return leave policy including all related models
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalRoute', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave policies updated successful',
            'leave_policy' => $detailPolicy
        ]);
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
        Category::where('id', $item['id'])
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
        Category::create($arr);
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

    public function withoutEntitlement($leavePolicy, $employee, $startYear, $endYear)
    {
        $arr = [
            'leave_policy_id' => $leavePolicy->id,
            'employee_id' => $employee['id'],
            'cycle_start_date' => $startYear,
            'cycle_end_date' => $endYear,
        ];
        Entitlement::create($arr);
    }

    public function yearly($employees, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear)
    {
        foreach ($employees as $employee) {
            $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
            $year_of_service = $currentDate->diffInYears($joined_date);
            foreach ($leaveEntitlements as $leaveEntitlement) {
                if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                    if (in_array($employee['gender'], $categoriesArr) && in_array($employee['marital_status'], $categoriesArr) && in_array($employee['employment_type'], $categoriesArr)) {
                        $arr = [
                            'leave_policy_id' => $leavePolicy->id,
                            'employee_id' => $employee['id'],
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

    public function monthly($employees, $months, $currentDate, $leaveEntitlements, $categoriesArr, $leavePolicy)
    {
        foreach ($employees as $employee) {
            foreach ($months as $month) {
                $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                $year_of_service = $currentDate->diffInYears($joined_date);
                foreach ($leaveEntitlements as $leaveEntitlement) {
                    if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                        if (in_array($employee['gender'], $categoriesArr) && in_array($employee['marital_status'], $categoriesArr) && in_array($employee['employment_type'], $categoriesArr)) {
                            $arr = [
                                'leave_policy_id' => $leavePolicy->id,
                                'employee_id' => $employee['id'],
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
};
