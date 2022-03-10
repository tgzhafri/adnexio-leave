<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCarryForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_carry_forwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entitlement_id')->constrained('leave_entitlements')->onDelete('cascade');
            $table->string('from_year');
            $table->date('expiry_date');
            $table->float('amount', 8, 2);
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
        Schema::dropIfExists('leave_carry_forwards');
    }
}
