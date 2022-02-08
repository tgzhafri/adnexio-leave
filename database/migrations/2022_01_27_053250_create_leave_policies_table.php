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
            $table->enum('accrual_happen', ['start_month', 'end_month'])->nullable();
            $table->foreignId('approval_config_id')->constrained('approval_configs')->onDelete('cascade');
            $table->string('carry_forward_amount')->nullable();
            $table->string('carry_forward_expiry')->nullable();
            $table->boolean('leave_credit')->nullable();
            $table->string('leave_credit_expiry')->nullable();
            $table->string('daily_quota')->nullable();
            $table->integer('restriction_amount')->nullable();
            $table->boolean('proof_required')->nullable();
            $table->boolean('description_required')->nullable();
            $table->boolean('half_day_option')->nullable();
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
