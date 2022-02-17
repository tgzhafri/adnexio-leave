<?php

namespace Database\Seeders;

use App\Models\LeavePolicy;
use Illuminate\Database\Seeder;
use File;

class LeavePolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // LeavePolicy::truncate();
        $json = File::get("database/data/LeavePolicyData.json");
        $policies = json_decode($json);
        foreach ($policies as $key => $data) {
            LeavePolicy::create([
                'company_id' => $data->company_id,
                'name' => $data->name,
                'abbreviation' => $data->abbreviation,
                'description' => $data->description,
                'color' => $data->color,
                'document_required' => $data->document_required,
                'reason_required' => $data->reason_required,
                'half_day_option' => $data->half_day_option,
                'cycle_period' => $data->cycle_period,
                'eligible_amount' => $data->eligible_amount,
                'eligible_period' => $data->eligible_period,
                'accrual_option' => $data->accrual_option,
                'accrual_happen' => $data->accrual_happen,
                'approval_config_id' => $data->approval_config_id,
                'leave_quota_amount' => $data->leave_quota_amount,
                'leave_quota_unit' => $data->leave_quota_unit,
                'leave_quota_category' => $data->leave_quota_category,
                'restriction_amount' => $data->restriction_amount,
                'day_prior' => $data->day_prior,
                'carry_forward_amount' => $data->carry_forward_amount,
                'carry_forward_expiry' => $data->carry_forward_expiry,
                'leave_credit_instant_use' => $data->leave_credit_instant_use,
                'leave_credit_expiry_amount' => $data->leave_credit_expiry_amount,
                'leave_credit_expiry_period' => $data->leave_credit_expiry_period,
                'status' => $data->status,
            ]);
        }
    }
}