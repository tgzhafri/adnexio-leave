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
            $table->foreignId('entitlement_id')->constrained('entitlements')->onDelete('cascade');
            $table->integer('amount');
            $table->date('completed_date');
            $table->date('expiry_date');
            $table->string('status');
            $table->string('assign_by');
            $table->string('assign_to');
            $table->enum('tag', ['leave', 'in_lieu']);
            $table->string('acknowledgement_superior');
            $table->string('acknowledgement_employee');
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
