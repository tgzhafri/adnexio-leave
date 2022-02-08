<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->string('first_approval');
            $table->string('first_approval_status');
            $table->timestamp('first_approval_timestamp');
            $table->string('second_approval')->nullable();
            $table->string('second_approval_status')->nullable();
            $table->timestamp('second_approval_timestamp')->nullable();
            $table->string('third_approval')->nullable();
            $table->string('third_approval_status')->nullable();
            $table->timestamp('third_approval_timestamp')->nullable();
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
        Schema::dropIfExists('approvals');
    }
}
