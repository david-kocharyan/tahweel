<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInspectionIdToCasomerWarantySaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('castomer_warranty_saves', function (Blueprint $table) {
            $table->unsignedBigInteger("inspection_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('castomer_warranty_saves', function (Blueprint $table) {
            //
        });
    }
}
