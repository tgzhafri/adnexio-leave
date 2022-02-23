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
        // $faker = Faker::create();

        // return [
        //     'user_id' => function () { // create user id as well for each employee created
        //         return User::factory()->create()->id;
        //     },
        // ];
    }
}
