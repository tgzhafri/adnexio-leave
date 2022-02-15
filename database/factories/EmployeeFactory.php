<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;


class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();

        return [
            'company_id' => 1,
            'user_id' => function () { // create user id as well for each employee created
                return User::factory()->create()->id;
            },
            'employee_no' => $faker->randomNumber(),
            'dob' => $faker->date(),
            'joined_date' => $faker->date(),
            'employment_type' => $faker->randomElement([
                'permanent',
                'contract'
            ]),
            'profile_photo' => $faker->imageUrl(),
            'gender' => $faker->randomElement([
                'male',
                'female'
            ]),
            'marital_status' => $faker->randomElement([
                'single',
                'married',
                'divorced',
                'widowed'
            ]),
            'status' => 1
        ];
    }
}
