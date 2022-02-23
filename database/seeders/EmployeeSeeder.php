<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use File;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Employee::factory(10)->create();
        $json = File::get("database/data/EmployeeData.json");
        $employees = json_decode($json);
        foreach ($employees as $key => $data) {
            Employee::create([
                'parent_id' => $data->parent_id,
                'name' => $data->name,
                'company_id' => $data->company_id,
                'user_id' => $data->user_id,
                'dob' => $data->dob,
                'department_id' => $data->department_id,
                'job_title' => $data->job_title,
                'position_id' => $data->position_id,
                'employee_no' => $data->employee_no,
                'profile_photo' => $data->profile_photo,
                'joined_date' => $data->joined_date,
                'gender' => $data->gender,
                'marital_status' => $data->marital_status,
                'employment_type' => $data->employment_type,
                'status' => $data->status,
            ]);
        }
    }
}
