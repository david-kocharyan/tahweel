<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastomerWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('castomer_warranties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("inspection_id");
            $table->unsignedBigInteger('customer_id');
            $table->unsignedTinyInteger('warranty_type');
            $table->timestamps();

            $table->foreign("inspection_id")->references("id")->on("inspection_forms")->onDelete("cascade");
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
        Schema::dropIfExists('castomer_warranties');
    }
}
