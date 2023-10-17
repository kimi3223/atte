<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('start_time')->default(now());;
            $table->dateTime('end_time')->nullable();
            $table->integer('break_time')->default(0);
            $table->dateTime('break_start_time')->nullable();
            $table->dateTime('break_end_time')->nullable();
            $table->timestamps();

            $table->dateTime('start_time')->default(now())->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
