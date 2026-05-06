<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user_code')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('gender')->default(1);

            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->foreign('clinic_id')->references('id')->on('clinics')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->foreign('specialty_id')->references('id')->on('specialties')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('city_id')->default(0)->index()->nullable();
            $table->integer('district_id')->default(0)->index()->nullable();
            $table->integer('street_id')->default(0)->index()->nullable();

            $table->tinyInteger('position')->nullable();
            $table->text('description')->nullable();
            $table->text('contents')->nullable();

            $table->bigInteger('price_min')->nullable();
            $table->bigInteger('price_max')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
