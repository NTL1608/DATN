<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('patient_id')->nullable();
            $table->foreign('patient_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('book_for')->default(1)->nullable();
            $table->integer('city_id')->default(0)->index()->nullable();
            $table->integer('district_id')->default(0)->index()->nullable();
            $table->integer('street_id')->default(0)->index()->nullable();
            $table->string('address', 255)->nullable();
            $table->text('reason_other');
            $table->date('date_booking')->nullable();
            $table->string('time_booking')->nullable();
            $table->string('time_type')->nullable();
            $table->string('file_result')->nullable();
            $table->text('note')->nullable();
            $table->bigInteger('price')->nullable();
            $table->tinyInteger('status')->default(1)->nullable();

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
        Schema::dropIfExists('bookings');
    }
}
