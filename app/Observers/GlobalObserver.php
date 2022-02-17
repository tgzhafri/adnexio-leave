<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Entitlement;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class GlobalObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the LeavePolicy "created" event.
     *
     * @param  \App\Models\LeavePolicy  $leavePolicy
     * @return void
     */
    public function created(Model $model)
    {

        // //------ code start ---- Insert employee entitlement according to the requirement setup ----------////
        $employees = Employee::all();
        $leaveEntitlements = LeaveEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->get()->toArray();

        $categoriesData = Category::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('data')->get()->toArray();
        $dataArr = Arr::flatten($categoriesData);
        // dd($dataArr);
        $startYear = Carbon::now()->startOfYear();
        $endYear = Carbon::now()->endOfYear();
        $current_date = Carbon::now();

        if ($leavePolicy->cycle_period == 'yearly') {
            foreach ($employees as $employee) {
                $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                $year_of_service = $current_date->diffInYears($joined_date);
                foreach ($leaveEntitlements as $leaveEntitlement) {
                    if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                        if (in_array($employee['gender'], $dataArr) && in_array($employee['marital_status'], $dataArr) && in_array($employee['employment_type'], $dataArr)) {
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
            ////------- method to list all 12 months in a year using carbon-----------//
            $period = CarbonPeriod::create($startYear, '1 month', $endYear);
            foreach ($period as $month) {
                $months[] = [
                    "start_date" => Carbon::parse($month)->format('Y-m-d'),
                    "end_date" => Carbon::parse($month)->endOfMonth()->format('Y-m-d'),
                ];
            }
            ////------- method to list all 12 months in a year using carbon-----------//
            foreach ($employees as $employee) {
                foreach ($months as $month) {
                    $joined_date = Carbon::createFromFormat('Y-m-d', $employee['joined_date']);
                    $year_of_service = $current_date->diffInYears($joined_date);
                    foreach ($leaveEntitlements as $leaveEntitlement) {
                        if ($year_of_service >= $leaveEntitlement['start_year_of_service'] && $year_of_service < $leaveEntitlement['end_year_of_service']) {
                            if (in_array($employee['gender'], $dataArr) && in_array($employee['marital_status'], $dataArr) && in_array($employee['employment_type'], $dataArr)) {
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
        }
        // //------ code end ---- Insert employee entitlement according to the requirement setup ----------////

    }

    /**
     * Handle the LeavePolicy "updated" event.
     *
     * @param  \App\Models\LeavePolicy  $leavePolicy
     * @return void
     */
    public function updated(LeavePolicy $leavePolicy)
    {
        //
    }

    /**
     * Handle the LeavePolicy "deleted" event.
     *
     * @param  \App\Models\LeavePolicy  $leavePolicy
     * @return void
     */
    public function deleted(LeavePolicy $leavePolicy)
    {
        //
    }

    /**
     * Handle the LeavePolicy "restored" event.
     *
     * @param  \App\Models\LeavePolicy  $leavePolicy
     * @return void
     */
    public function restored(LeavePolicy $leavePolicy)
    {
        //
    }

    /**
     * Handle the LeavePolicy "force deleted" event.
     *
     * @param  \App\Models\LeavePolicy  $leavePolicy
     * @return void
     */
    public function forceDeleted(LeavePolicy $leavePolicy)
    {
        //
    }
}
