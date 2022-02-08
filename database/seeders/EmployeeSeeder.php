<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            // this method will include datetime
            $employee = new Employee(); // make temperory row
            $employee->name = $faker->name();  // create fake info
            $employee->employee_no = $faker->randomNumber();
            $employee->dob = $faker->date();
            $employee->joined_date = $faker->date();
            $employee->employment_type = 'permanent';
            $employee->profile_photo = $faker->imageUrl();
            $employee->gender = 'male';
            $employee->marital_status = 'single';
            $employee->status = 1;
            $employee->save(); // save new info and automatically save created and updated date
        }
    }
}
