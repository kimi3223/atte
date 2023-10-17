<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBreakColumnsAndAddWorkTimeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('break_start_time');
                $table->dropColumn('break_end_time');

                $table->integer('work_time')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('work_time');

            $table->timestamp('break_start_time')->nullable();
            $table->timestamp('break_end_time')->nullable();
    });
    }
}
