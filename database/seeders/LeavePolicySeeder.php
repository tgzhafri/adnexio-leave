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
                // 'company_id' => $data->company_id,
                'name' => $data->name,
                'abbreviation' => $data->abbreviation,
                'description' => $data->description,
                'color' => $data->color,
                'attachment_required' => $data->attachment_required,
                'type' => $data->type,
                'reason_required' => $data->reason_required,
                'half_day_option' => $data->half_day_option,
                'credit_deduction' => $data->credit_deduction,
                'credit_expiry_amount' => $data->credit_expiry_amount,
                'credit_expiry_period' => $data->credit_expiry_period,
                'cycle_period' => $data->cycle_period,
                'eligible_amount' => $data->eligible_amount,
                'eligible_period' => $data->eligible_period,
                'accrual_option' => $data->accrual_option,
                'accrual_happen' => $data->accrual_happen,
                // 'approval_route_id' => $data->approval_route_id,
                'quota_amount' => $data->quota_amount,
                'quota_unit' => $data->quota_unit,
                'quota_category' => $data->quota_category,
                'restriction_amount' => $data->restriction_amount,
                'day_prior' => $data->day_prior,
                'carry_forward_amount' => $data->carry_forward_amount,
                'carry_forward_expiry' => $data->carry_forward_expiry,
                // 'status' => $data->status,
            ]);
        }
    }
}
