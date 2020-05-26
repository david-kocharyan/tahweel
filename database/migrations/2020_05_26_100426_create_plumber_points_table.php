<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlumberPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plumber_points', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger("inspection_id");
            $table->foreign("inspection_id")->references("id")->on("inspections")->onDelete("cascade");

            $table->decimal("point", 8, 2);
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
        Schema::dropIfExists('plumber_points');
    }
}
