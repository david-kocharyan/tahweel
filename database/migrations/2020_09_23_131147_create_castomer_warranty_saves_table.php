<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastomerWarrantySavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('castomer_warranty_saves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("inspector_id");
            $table->unsignedBigInteger('customer_id');
            $table->unsignedTinyInteger('warranty_type');
            $table->unsignedTinyInteger('phase');
            $table->timestamps();

            $table->foreign("inspector_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('castomer_warranty_saves');
    }
}
