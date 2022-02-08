<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('abbreviation', 6);
            $table->string('description', 125);
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->enum('cycle_period', ['monthly', 'yearly']);
            $table->enum('accrual_option', ['full_amount', 'prorate']);
            $table->enum('accrual_happens', ['beginning_month', 'end_month']);
            $table->foreignId('approval_config_id')->constrained('approval_configs')->onDelete('cascade');
            $table->string('carry_forward_amount');
            $table->string('carry_forward_expiry'); 
            $table->boolean('leave_credit'); 
            $table->string('leave_credit_expiry'); 
            $table->string('daily_quota');
            $table->integer('restriction_amount');
            $table->boolean('proof_required');
            $table->boolean('description_required');
            $table->boolean('half_day_option');
            $table->integer('status');
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
        Schema::dropIfExists('leave_policies');
    }
}
