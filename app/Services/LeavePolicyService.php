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

        $leaveEntitlement = $request->entitlement; // update leave entitlement table
        foreach ($leaveEntitlement as $item) {
            $arr = [
                'leave_policy_id' => $leavePolicy->id,
                'layer' => $item['layer'],
                'amount' => $item['amount'],
                'start_year_of_service' => $item['start_year_of_service'],
                'end_year_of_service' => $item['end_year_of_service'],
            ];
            LeaveEntitlement::create($arr);
        }
        // method to insert multiple/array of requests into db of related model
        $categoryArr = $request->category; // update category table
        foreach ($categoryArr as $item) {
            $arr = [
                'leave_policy_id' => $leavePolicy->id,
                'name' => $item['name'],
                'data' => $item['data'],
                'status' => $item['status']
            ];
            Category::create($arr);
        }

        // method to insert single request into db of related model
        // $category->fill([
        //     'leave_policy_id' => $leavePolicy->id,
        //     'name' => $request->name,
        //     'data' => $request->data
        // ]);
        // $leavePolicy->category()->save($category);

        // //------ code start ---- Insert employee entitlement according to the requirement setup ----------////
        $employees = Employee::all();
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('data')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);

        // ---------- start code for date manipulation ---------------- //
        $startYear = Carbon::now()->startOfYear();
        $endYear = Carbon::now()->endOfYear();
        $current_date = Carbon::now();
        ////------- method to list all 12 months in a year using carbon-----------//
        $period = CarbonPeriod::create($startYear, '1 month', $endYear);
        foreach ($period as $month) {
            $months[] = [
                "start_date" => Carbon::parse($month)->format('Y-m-d'),
                "end_date" => Carbon::parse($month)->endOfMonth()->format('Y-m-d'),
            ];
        }
        ////------- method to list all 12 months in a year using carbon-----------//
        // ---------- end code for date manipulation ---------------- //

        if ($leavePolicy->cycle_period == 'yearly') {
            foreach ($employees as $employee) {
                $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                $year_of_service = $current_date->diffInYears($joined_date);
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
                            // dd($arr);
                            Entitlement::create($arr);
                        }
                    }
                }
            }
        }

        if ($leavePolicy->cycle_period == 'monthly') {
            foreach ($employees as $employee) {
                foreach ($months as $month) {
                    $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                    $year_of_service = $current_date->diffInYears($joined_date);
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
                                // dd($arr);
                                Entitlement::create($arr);
                            }
                        }
                    }
                }
            }
        }
        // //------ code end ---- Insert employee entitlement according to the requirement setup ----------////

        // to return every related models to leave policy
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalConfig', 'category'])
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

        $entitlements = $request->entitlement;
        foreach ($entitlements as $item) {
            if (isset($item['id'])) {
                LeaveEntitlement::where('id', $item['id'])
                    ->update([
                        'layer' => $item['layer'],
                        'amount' => $item['amount'],
                        'start_year_of_service' => $item['start_year_of_service'],
                        'end_year_of_service' => $item['end_year_of_service'],
                    ]);
            } else {
                $arr = [
                    'leave_policy_id' => $leavePolicy->id,
                    'layer' => $item['layer'],
                    'amount' => $item['amount'],
                    'start_year_of_service' => $item['start_year_of_service'],
                    'end_year_of_service' => $item['end_year_of_service'],
                ];
                LeaveEntitlement::create($arr);
            }
        }
        // method to insert multiple/array of requests into db of related model
        $categoryArr = $request->category;
        foreach ($categoryArr as $item) {
            // check if item existed, just update the new data
            if (isset($item['id'])) {
                Category::where('id', $item['id'])
                    ->update([
                        'leave_policy_id' => $leavePolicy->id,
                        'name' => $item['name'],
                        'data' => $item['data'],
                        'status' => $item['status'],
                    ]);
            } else { // check if item not exist, create and store new data
                $arr = [
                    'leave_policy_id' => $leavePolicy->id,
                    'name' => $item['name'],
                    'data' => $item['data'],
                    'status' => $item['status']
                ];
                Category::create($arr);
            }
        }

        // //------ code start ---- Insert employee entitlement according to the requirement setup ----------////
        $employees = Employee::all();
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('data')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);
        $entitlements = Entitlement::where('leave_policy_id', $leavePolicy->id)->first();
        // dd($entitlements);

        // ---------- start code for date manipulation ---------------- //
        $startYear = Carbon::now()->startOfYear();
        $endYear = Carbon::now()->endOfYear();
        $current_date = Carbon::now();
        ////------- method to list all 12 months in a year using carbon-----------//
        $period = CarbonPeriod::create($startYear, '1 month', $endYear);
        foreach ($period as $month) {
            $months[] = [
                "start_date" => Carbon::parse($month)->format('Y-m-d'),
                "end_date" => Carbon::parse($month)->endOfMonth()->format('Y-m-d'),
            ];
        }
        ////------- method to list all 12 months in a year using carbon-----------//
        // ---------- end code for date manipulation ---------------- //

        // ------------ remove existing entitlement data and create new updated data -----------//
        if ($entitlements) {
            Entitlement::where('leave_policy_id', $leavePolicy->id)->forceDelete();
        }
        // ------------ remove existing entitlement data and create new updated data -----------//

        if ($leavePolicy->cycle_period == 'yearly') { //------ cycle period YEARLY ----------//
            foreach ($employees as $employee) {
                $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                $year_of_service = $current_date->diffInYears($joined_date);
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

        if ($leavePolicy->cycle_period == 'monthly') { // -------- cycle period MONTHLY ----------- //
            foreach ($employees as $employee) {
                foreach ($months as $month) {
                    $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                    $year_of_service = $current_date->diffInYears($joined_date);
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
        // //------ code end ---- Insert employee entitlement according to the requirement setup ----------////

        // return leave policy including all related models
        $detailPolicy = LeavePolicy::whereId($leavePolicy->id)
            ->with(['leaveEntitlement', 'approvalConfig', 'category'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leave policies updated successful',
            'leave_policy' => $detailPolicy
        ]);
    }
};
