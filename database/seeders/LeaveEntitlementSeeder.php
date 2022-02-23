<?php

namespace Database\Seeders;

use App\Models\LeaveEntitlement;
use Illuminate\Database\Seeder;
use File;

class LeaveEntitlementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/LeaveEntitlementData.json");
        $entitlements = json_decode($json);
        foreach ($entitlements as $key => $data) {
            LeaveEntitlement::create([
                'leave_policy_id' => $data->leave_policy_id,
                'layer' => $data->layer,
                'amount' => $data->amount,
                'start_year_of_service' => $data->start_year_of_service,
                'end_year_of_service' => $data->end_year_of_service,
            ]);
        }
    }
}
