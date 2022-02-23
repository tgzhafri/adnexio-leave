<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use File;

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
        $positions = json_decode($json);
        foreach ($positions as $key => $data) {
            Position::create([
                'name' => $data->name,
            ]);
        }
    }
}
