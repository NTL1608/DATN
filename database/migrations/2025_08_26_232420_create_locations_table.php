<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('loc_name', 255)->nullable();
            $table->string('loc_name_lower', 255)->nullable();
            $table->string('loc_slug', 255)->nullable();
            $table->integer('loc_parent_id')->nullable();
            $table->integer('loc_city_id')->nullable();
            $table->integer('loc_district_id')->nullable();
            $table->integer('loc_street_id')->nullable();
            $table->integer('loc_ward_id')->nullable();
            $table->integer('loc_level')->nullable();
            $table->integer('loc_code')->nullable();
            $table->string('loc_type', 255)->nullable();
            $table->string('loc_description', 255)->nullable();
            $table->string('loc_description_full', 255)->nullable();
            $table->tinyInteger('loc_status')->nullable();
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
        Schema::dropIfExists('locations');
    }
}
