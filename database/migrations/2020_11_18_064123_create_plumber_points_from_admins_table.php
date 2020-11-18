<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlumberPointsFromAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plumber_points_from_admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("plumber_id");
            $table->integer('points');
            $table->timestamps();

            $table->foreign("plumber_id")->references("id")->on("users")->onDelete("cascade")->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plumber_points_from_admins');
    }
}
