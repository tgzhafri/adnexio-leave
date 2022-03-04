<?php

namespace Database\Seeders;

use App\Models\LeaveCategory;
use Illuminate\Database\Seeder;
use File;

class LeaveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/LeaveCategoryData.json");
        $categories = json_decode($json);
        foreach ($categories as $key => $data) {
            LeaveCategory::create([
                'leave_policy_id' => $data->leave_policy_id,
                'name' => $data->name,
            ]);
        }
    }
}
