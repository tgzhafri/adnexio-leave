<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entitlement_id')->constrained('leave_entitlements')->onDelete('cascade');
            $table->foreignId('leave_request_id')->constrained('leave_requests')->onDelete('cascade');
            $table->float('requested', 8, 2);
            $table->float('granted', 8, 2)->nullable();
            $table->float('rejected', 8, 2)->nullable();
            $table->float('utilised', 8, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default(1);
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
        Schema::dropIfExists('leave_credits');
    }
}
