<?php

namespace Database\Seeders;

use App\Models\Workday;
use Illuminate\Database\Seeder;
use File;

class WorkdaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/WorkdayData.json");
        $workday = json_decode($json);
        foreach ($workday as $key => $data) {
            Workday::create([
                'company_id' => $data->company_id,
                'day' => $data->day,
                'type' => $data->type,
            ]);
        }
    }
}
