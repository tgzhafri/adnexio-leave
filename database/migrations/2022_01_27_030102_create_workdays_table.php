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
            $table->enum('Monday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Tuesday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Wednesday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Thursday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Friday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Saturday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
            $table->enum('Sunday', ['full_day', 'half_day_am', 'half_day_pm', 'off_day', 'rest_day']);
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
