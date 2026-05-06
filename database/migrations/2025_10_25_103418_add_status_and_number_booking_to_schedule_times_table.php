<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndNumberBookingToScheduleTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_times', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->after('time_schedule')->comment('0: available, 1: full');
            $table->integer('number_booking')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_times', function (Blueprint $table) {
            $table->dropColumn(['status', 'number_booking']);
        });
    }
}
