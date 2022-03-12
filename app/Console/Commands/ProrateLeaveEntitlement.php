<?php

namespace App\Console\Commands;

use App\Enums\AccrualType;
use App\Enums\LeavePeriodType;
use App\Models\LeaveCategory;
use App\Models\LeaveDate;
use App\Models\LeaveEntitlement;
use App\Models\LeavePolicy;
use App\Models\LeavePolicyEntitlement;
use App\Models\Staff;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ProrateLeaveEntitlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:prorate-leave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update prorated leave entitlement for each employee monthly';

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
        \Log::info("Cron is working fine!");

        $leavePolicies = LeavePolicy::where('accrual_type', AccrualType::Prorate)->select('id')->get();
        $leavePolicy = LeavePolicy::where('accrual_type', AccrualType::Prorate)->select('id')->first();
        $staffs = Staff::get();
        $leaveEntitlements = LeaveEntitlement::get();
        $leavePolicyEntitlements = LeavePolicyEntitlement::where('leave_policy_id', $leavePolicy->id)->get()->toArray(); // toArray() return collection into a simplified array
        $categories = LeaveCategory::where([
            ['leave_policy_id', '=', $leavePolicy->id],
            ['status', '=', 1]
        ])->select('data')->get()->toArray();
        $categoriesArr = Arr::flatten($categories);

        $months = $this->cyclePeriod()['months'];
        $startYear = $this->cyclePeriod()['startYear'];
        $endYear = $this->cyclePeriod()['endYear'];
        $currentDate = $this->cyclePeriod()['currentDate'];

        foreach ($staffs as $staff) {
            $joined_date = Carbon::createFromFormat('Y-m-d', $staff['joined_date']);
            $year_of_service = $currentDate->diffInYears($joined_date);
            foreach ($leavePolicyEntitlements as $leavePolicyEntitlement) {
                if ($year_of_service >= $leavePolicyEntitlement['start_year_of_service'] && $year_of_service < $leavePolicyEntitlement['end_year_of_service']) {
                    if (in_array($staff['gender'], $categoriesArr) && in_array($staff['marital_status'], $categoriesArr) && in_array($staff['employment_type'], $categoriesArr)) {
                        $arr = [
                            'leave_policy_id' => $leavePolicy->id,
                            'staff_id' => $staff['id'],
                            'cycle_start_date' => $startYear,
                            'cycle_end_date' => $endYear,
                            'amount' => $leavePolicyEntitlement['amount'],
                            'balance' => $leavePolicyEntitlement['amount']
                        ];
                        LeaveEntitlement::create($arr);
                    }
                }
            }
        }


        $leavePolicies->map(function ($leavePolicy, $key) use ($staffs) {
            $staffs->map(function ($staff, $key) use ($leavePolicy) {
                $leaveDate = LeaveDate::whereHas('leaveRequest', function ($query) use ($leavePolicy, $staff) {
                    $query->where([
                        ['leave_policy_id', $leavePolicy['id']],
                        ['staff_id', $staff['id']],
                    ]);
                })->with(['leaveRequest' => function ($query) {
                    return $query->select(['id', 'staff_id', 'leave_policy_id']);
                }])->get()->toArray();
            });
        });

        if ($leavePolicy->accrual_type == AccrualType::Prorate) {
            $currentMonth = Carbon::now()->format('m');
            $prevMonth = Carbon::now()->subMonth()->format('m');
            $currentDate = Carbon::now()->startOfDay()->format('Y-m-d');
            $startOfCurrentMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfCurrentMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

            if ($leavePolicy->accrual_happen == LeavePeriodType::StartMonth) {
                if ($currentDate >= $startOfCurrentMonth) {
                    $prorated = $this->amount / 12 * $currentMonth;

                    LeaveEntitlement::whereId($this->id)->update([
                        'amount' => $prorated,
                        'balance' => $prorated,
                    ]);
                }
            }
            if ($leavePolicy->accrual_happen == LeavePeriodType::EndMonth) {
                if ($currentDate >= $endOfCurrentMonth) {
                    $prorated = $this->balance / 12 * $currentMonth;
                    return [
                        'amount' => $prorated,
                        'balance' => $prorated
                    ];
                } else {
                    $prorated = $this->balance / 12 * $prevMonth;
                    return [
                        'amount' => $prorated,
                        'balance' => $prorated
                    ];
                }
            }
        }
    }
    public function cyclePeriod()
    {
        $startYear = Carbon::now()->startOfYear();
        $endYear = Carbon::now()->endOfYear();
        $currentDate = Carbon::now();

        ////*------- method to list all 12 months in a year using carbon-----------//
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
        ////*------- method to list all 12 months in a year using carbon-----------//
        return $data;
    }
}
