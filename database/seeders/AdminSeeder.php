<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::firstOrCreate(
            [
                'id' => 1,
            ],
            [
                'company_id' => 1,
                'employee_id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@adnexio.com',
                'password' => bcrypt('password'),
                'role' => 1,
            ]
        );
    }
}
