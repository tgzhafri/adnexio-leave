<?php

namespace Database\Seeders;

use App\Models\LeaveCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LeaveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $json = File::get("database/data/LeaveCategoryData.json");
        // $categories = json_decode($json);
        // foreach ($categories as $key => $data) {
        //     LeaveCategory::create([
        //         'leave_policy_id' => $data->leave_policy_id,
        //         'name' => $data->name,
        //     ]);
        // }

        $json = File::get("database/data/LeaveCategoryData.json");
        $data = json_decode($json, true);

        foreach ($data as $value) {
            $array[] = $value;
        }

        DB::table('leave_categories')->insert($array);
    }
}
