<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use File;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/CategoryData.json");
        $policies = json_decode($json);
        foreach ($policies as $key => $data) {
            Category::create([
                'leave_policy_id' => $data->leave_policy_id,
                'name' => $data->name,
                'data' => $data->data,
            ]);
        }
    }
}
