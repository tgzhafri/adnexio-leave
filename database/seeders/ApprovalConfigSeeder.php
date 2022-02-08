<?php

namespace Database\Seeders;

use App\Models\ApprovalConfig;
use Illuminate\Database\Seeder;

class ApprovalConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $approval = new ApprovalConfig();
        $approval->name = 'Default Approval Layer';
        $approval->first_approval = 'Head of Department';
        $approval->second_approval = 'HR Admin';
        $approval->save();
    }
}
