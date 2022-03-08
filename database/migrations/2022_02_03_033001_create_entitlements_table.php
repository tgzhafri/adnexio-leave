<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('leave_policy_id')->constrained('leave_policies')->onDelete('cascade');
            $table->date('cycle_start_date');
            $table->date('cycle_end_date');
            $table->float('amount', 8, 2)->nullable();
            $table->float('balance', 8, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entitlements');
    }
}
