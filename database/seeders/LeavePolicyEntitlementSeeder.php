<?php

namespace Database\Seeders;

use App\Models\LeavePolicyEntitlement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LeavePolicyEntitlementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $json = File::get("database/data/LeavePolicyEntitlementData.json");
        // $entitlements = json_decode($json);
        // foreach ($entitlements as $key => $data) {
        //     LeavePolicyEntitlement::create([
        //         'leave_policy_id' => $data->leave_policy_id,
        //         'layer' => $data->layer,
        //         'amount' => $data->amount,
        //         'start_year_of_service' => $data->start_year_of_service,
        //         'end_year_of_service' => $data->end_year_of_service,
        //     ]);
        // }

        $json = File::get("database/data/LeavePolicyEntitlementData.json");
        $data = json_decode($json, true);

        foreach ($data as $value) {
            $array[] = $value;
        }

        DB::table('leave_policy_entitlements')->insert($array);
    }
}
