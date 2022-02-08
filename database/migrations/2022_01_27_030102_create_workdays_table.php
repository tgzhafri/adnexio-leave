<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->enum('Monday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Tuesday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Wednesday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Thursday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Friday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Saturday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
            $table->enum('Sunday', ['workday', 'workday(AM)', 'workday(PM)', 'off_day', 'rest_day']);
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
        Schema::dropIfExists('workdays');
    }
}
