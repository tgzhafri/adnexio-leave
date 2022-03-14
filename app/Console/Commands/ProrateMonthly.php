<?php

namespace App\Console\Commands;

use App\Enums\AccrualType;
use App\Enums\LeaveCategoryType;
use App\Enums\LeavePeriodType;
use App\Models\LeaveCategory;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use App\Models\LeavePolicyEntitlement;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ProrateLeaveEntitlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:prorate-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prorated leave entitlement for each employee monthly basis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('cron:prorate-monthly is running');

        $leavePolicies = LeavePolicy::where('accrual_type', AccrualType::Prorate)->get();
        $staffs = Staff::get();

        foreach ($leavePolicies as $leavePolicy) {
            $leavePolicyEntitlements = LeavePolicyEntitlement::where('leave_policy_id', $leavePolicy['id'])->get()->toArray(); // toArray() return collection into a simplified array
            $categories = LeaveCategory::where([
                ['leave_policy_id', '=', $leavePolicy['id']],
                ['status', '=', 1]
            ])->select('data')->get()->toArray();
            $categoriesArr = Arr::flatten($categories);

            $startYear = Carbon::now()->startOfYear();
            $endYear = Carbon::now()->endOfYear();
            $currentDate = Carbon::now()->startOfDay();

            // //*------ code start ---- Prorate staff entitlement according to the requirement setup ----------////

            if ($leavePolicy['cycle_period'] == LeavePeriodType::Yearly) {
                $this->prorate($staffs, $currentDate, $leavePolicyEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear);
            } else {
                return "Error: Leave policy does not have a yearly cycle period";
            }
            // //*------ code end ---- Prorate staff entitlement according to the requirement setup ----------////
        }

        $this->info('Successfully update prorated leave for this month.');
    }

    public function prorate($staffs, $currentDate, $leavePolicyEntitlements, $categoriesArr, $leavePolicy, $startYear, $endYear)
    {
        foreach ($staffs as $staff) {
            $leaveEntitlement = LeaveEntitlement::where([
                ['leave_policy_id', $leavePolicy['id']],
                ['staff_id', $staff['id']],
            ])->first();
            $joinedDate = Carbon::createFromFormat('Y-m-d', $staff['joined_date']);
            $joinedMonth = $joinedDate->startOfMonth();
            $yearOfService = $currentDate->diffInYears($joinedDate);
            $diffMonth = $currentDate->diffInMonths($joinedMonth);
            foreach ($leavePolicyEntitlements as $leavePolicyEntitlement) {
                if (
                    $yearOfService >= $leavePolicyEntitlement['start_year_of_service']
                    && $yearOfService < $leavePolicyEntitlement['end_year_of_service']
                ) {
                    if (
                        in_array($staff[LeaveCategoryType::Gender], $categoriesArr)
                        && in_array($staff[LeaveCategoryType::MaritalStatus], $categoriesArr)
                        && in_array($staff[LeaveCategoryType::EmploymentType], $categoriesArr)
                    ) {
                        $currentMonth = Carbon::now()->format('m');
                        $prevMonth = Carbon::now()->subMonth()->format('m');
                        // $currentDate = Carbon::now()->startOfDay()->format('Y-m-d');
                        $startOfCurrentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
                        $endOfCurrentMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

                        if ($leavePolicy['accrual_happen'] == LeavePeriodType::StartMonth) {
                            if ($currentDate >= $startOfCurrentMonth) {
                                $prorated = $leavePolicyEntitlement['amount'] / 12 * $currentMonth;
                                $perMonth = $leavePolicyEntitlement['amount'] / 12;
                                $balance = $leaveEntitlement->balance + $perMonth;

                                LeaveEntitlement::where([
                                    ['leave_policy_id', $leavePolicy['id']],
                                    ['staff_id', $staff['id']],
                                ])->update([
                                    'amount' => $prorated,
                                    'balance' => $balance,
                                ]);
                            }
                        }
                        if ($leavePolicy['accrual_happen'] == LeavePeriodType::EndMonth) {
                            if ($currentDate >= $endOfCurrentMonth) {
                                $prorated = $leavePolicyEntitlement['amount'] / 12 * $currentMonth;
                                $perMonth = $leavePolicyEntitlement['amount'] / 12;
                                $balance = $leaveEntitlement->balance + $perMonth;

                                LeaveEntitlement::where([
                                    ['leave_policy_id', $leavePolicy['id']],
                                    ['staff_id', $staff['id']],
                                ])->update([
                                    'amount' => $prorated,
                                    'balance' => $balance,
                                ]);
                            } else {
                                $prorated = $leavePolicyEntitlement['amount'] / 12 * $prevMonth;
                                $perMonth = $leavePolicyEntitlement['amount'] / 12;
                                $balance = $leaveEntitlement->balance + $perMonth;

                                LeaveEntitlement::where([
                                    ['leave_policy_id', $leavePolicy['id']],
                                    ['staff_id', $staff['id']],
                                ])->update([
                                    'amount' => $prorated,
                                    'balance' => $balance,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
