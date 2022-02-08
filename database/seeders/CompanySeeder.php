<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index) {
            // this method will include datetime
            $company = new Company(); // make temperory row
            $company->name = $faker->company();  // create fake info
            $company->organisation_no = $faker->randomNumber();
            $company->logo = $faker->imageUrl();
            $company->address = $faker->address();
            $company->save(); // save new info and automatically save created and updated date
        }
    }
}
