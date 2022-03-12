<?php

use App\Enums\LeavePeriodType;
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
            // $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('abbreviation', 10);
            $table->string('description', 125)->nullable();
            $table->text('detail_description', 125)->nullable();
            $table->string('color')->nullable();
            $table->string('type'); // 0 - without entitlement, 1 - with entitlement, 2 - leave credit
            $table->boolean('attachment_required');
            $table->boolean('reason_required');
            $table->boolean('half_day_option');
            $table->boolean('credit_deduction');
            $table->integer('credit_expiry_amount')->nullable();
            $table->string('credit_expiry_period')->nullable();
            $table->string('cycle_period')->default(LeavePeriodType::Yearly);
            $table->integer('eligible_amount')->nullable();
            $table->string('eligible_period')->nullable();
            $table->string('accrual_type')->default('full_amount');
            $table->string('accrual_happen')->nullable();
            // $table->foreignId('approval_route_id')->constrained('approval_routes')->onDelete('cascade');
            $table->integer('quota_amount')->nullable();
            $table->string('quota_unit')->nullable();
            $table->string('quota_category')->nullable();
            $table->integer('restriction_amount')->nullable();
            $table->integer('day_prior')->nullable();
            $table->integer('carry_forward_amount')->nullable();
            $table->integer('carry_forward_expiry_amount')->nullable();
            $table->string('carry_forward_expiry_period')->nullable();
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
