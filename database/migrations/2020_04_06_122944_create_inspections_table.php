<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('plumber_id');
            $table->string('address', 191); //address
            $table->string('latitude', 191);
            $table->string('longitude', 191);
            $table->string('apartment', 191)->nullable();
            $table->string('project', 191);
            $table->unsignedTinyInteger('building_type');
            $table->timestamps();

            $table->foreign('plumber_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspections');
    }
}
