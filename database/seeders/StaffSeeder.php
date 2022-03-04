<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use File;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $json = File::get("database/data/StaffData.json");
        $staff = json_decode($json);
        foreach ($staff as $key => $data) {
            $employee = Staff::create([
                'parent_id' => $data->parent_id,
                'name' => $data->name,
                // 'company_id' => $data->company_id,
                // 'user_id' => $data->user_id,
                'dob' => $data->dob,
                'department_id' => $data->department_id,
                'job_title' => $data->job_title,
                'position_id' => $data->position_id,
                'role' => $data->role,
                'employee_no' => $data->employee_no,
                'profile_photo' => $data->profile_photo,
                'joined_date' => $data->joined_date,
                'gender' => $data->gender,
                'marital_status' => $data->marital_status,
                'employment_type' => $data->employment_type,
                'status' => $data->status,
            ]);
            // $role = Role::findByName($data->role);
            // $permissions = Permission::pluck('id', 'id')->all();
            // $role->syncPermissions($permissions);
            $employee->assignRole($data->role);
        }
    }
}
