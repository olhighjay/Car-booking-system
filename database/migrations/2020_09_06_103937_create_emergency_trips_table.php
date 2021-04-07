<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id')->nullable();
            $table->integer('car_id')->nullable();
            $table->integer('trip_request_id')->nullable();
            $table->integer('lga_id')->nullable();
            $table->string('name');
            $table->string('user_role')->nullable();
            $table->string('address');
            $table->date('trip_date');
            $table->time('trip_start_time');
            $table->time('trip_end_time');
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
        Schema::dropIfExists('emergency_trips');
    }
}
