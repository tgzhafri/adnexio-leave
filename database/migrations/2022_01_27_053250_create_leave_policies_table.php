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
            $table->boolean('document_required');
            $table->boolean('reason_required');
            $table->boolean('half_day_option');
            $table->enum('cycle_period', ['monthly', 'yearly']);
            $table->integer('eligible_amount')->nullable();
            $table->enum('eligible_period', ['day', 'week', 'month'])->nullable();
            $table->enum('accrual_option', ['full_amount', 'prorate']);
            $table->enum('accrual_happen', ['start_month', 'end_month'])->nullable();
            $table->foreignId('approval_config_id')->constrained('approval_configs')->onDelete('cascade');
            $table->integer('leave_quota_amount')->nullable();
            $table->enum('leave_quota_unit', ['percent', 'number'])->nullable();
            $table->enum('leave_quota_category', ['department', 'company'])->nullable();
            $table->integer('restriction_amount')->nullable();
            $table->integer('carry_forward_amount')->nullable();
            $table->string('carry_forward_expiry')->nullable();
            $table->boolean('leave_credit_instant_use')->nullable();
            $table->integer('leave_credit_expiry_amount')->nullable();
            $table->enum('leave_credit_expiry_period', ['cycle_period', 'day', 'week'])->nullable();
            $table->integer('status')->default(1); // 1 active, 0 archived
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
