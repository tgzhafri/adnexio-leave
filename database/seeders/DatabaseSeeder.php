<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ApprovalRouteSeeder::class,
            RolesAndPermissionsSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            LeavePolicySeeder::class,
            CategorySeeder::class,
            LeaveEntitlementSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
