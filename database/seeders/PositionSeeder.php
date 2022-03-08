<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/PositionData.json");
        $data = json_decode($json, true);

        foreach ($data as $value) {
            $array[] = $value;
        }

        DB::table('positions')->insert($array);
    }
}
