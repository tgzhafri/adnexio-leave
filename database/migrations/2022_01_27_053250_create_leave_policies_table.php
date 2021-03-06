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
            $table->string('abbreviation', 10);
            $table->string('description', 125);
            $table->string('color')->nullable();
            $table->boolean('with_entitlement');
            $table->boolean('document_required');
            $table->boolean('reason_required');
            $table->boolean('half_day_option');
            $table->boolean('credit_deduction');
            $table->integer('credit_expiry_amount')->nullable();
            $table->enum('credit_expiry_period', ['end_of_year', 'day', 'week', 'month'])->nullable();
            $table->enum('cycle_period', ['monthly', 'yearly'])->default('yearly');
            $table->integer('eligible_amount')->nullable();
            $table->enum('eligible_period', ['day', 'week', 'month'])->nullable();
            $table->enum('accrual_option', ['full_amount', 'prorate'])->default('full_amount');
            $table->enum('accrual_happen', ['start_month', 'end_month'])->nullable();
            $table->foreignId('approval_route_id')->constrained('approval_routes')->onDelete('cascade');
            $table->integer('leave_quota_amount')->nullable();
            $table->enum('leave_quota_unit', ['percent', 'number'])->nullable();
            $table->enum('leave_quota_category', ['department', 'company'])->nullable();
            $table->integer('restriction_amount')->nullable();
            $table->integer('day_prior')->nullable();
            $table->integer('carry_forward_amount')->nullable();
            $table->string('carry_forward_expiry')->nullable();
            $table->integer('status')->default(1);
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
