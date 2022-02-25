<?php

namespace Database\Seeders;

use App\Models\ApprovalRoute;
use Illuminate\Database\Seeder;

class ApprovalRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approval = new ApprovalRoute();
        $approval->name = 'Default Approval Layer';
        $approval->layer_one = 'supervisor';
        $approval->layer_two = 'admin';
        $approval->save();
    }
}
