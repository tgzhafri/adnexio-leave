<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use File;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/DepartmentData.json");
        $departments = json_decode($json);
        foreach ($departments as $key => $data) {
            Department::create([
                'name' => $data->name,
            ]);
        }
    }
}
