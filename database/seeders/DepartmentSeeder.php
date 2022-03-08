<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //     $json = File::get("database/data/DepartmentData.json");
        //     $departments = json_decode($json);
        //     foreach ($departments as $key => $data) {
        //         Department::create([
        //             'name' => $data->name,
        //         ]);
        //     }

        $json = File::get("database/data/DepartmentData.json");
        $data = json_decode($json, true);

        foreach ($data as $value) {
            $array[] = $value;
        }

        DB::table('departments')->insert($array);
    }
}
