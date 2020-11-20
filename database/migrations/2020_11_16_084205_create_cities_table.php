<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("country_id");
            $table->string('en', 191)->nullable();
            $table->string('ar', 191)->nullable();
            $table->string('ur', 191)->nullable();
            $table->timestamps();

            $table->foreign("country_id")->references("id")->on("countries")->onDelete("cascade")->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
