<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarryForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carry_forwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entitlement_id')->constrained('entitlements')->onDelete('cascade');
            $table->string('year');
            $table->date('expiry_date');
            $table->float('amount', 8, 2);
            $table->float('utilised', 8, 2);
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
        Schema::dropIfExists('carry_forwards');
    }
}
