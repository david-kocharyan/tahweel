<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_forms', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger("inspection_id");
            $table->foreign("inspection_id")->references("id")->on("inspections")->onDelete("cascade");

            $table->unsignedTinyInteger("pre_plaster")->default(0);
            $table->unsignedTinyInteger("before_tiles_installer")->default(0);
            $table->unsignedTinyInteger("final_after_finishing")->default(0);
            $table->unsignedTinyInteger("bathrooms_inspected")->default(0);
            $table->unsignedTinyInteger("kitchen_inspected")->default(0);
            $table->unsignedTinyInteger("service_counters_inspected")->default(0);

            $table->unsignedTinyInteger("bathroom_other_tahweel_materials")->default(0);
            $table->unsignedTinyInteger("bathroom_other_tahweel_valve")->default(0);
            $table->text("bathroom_other_technical_issue")->nullable();

            $table->unsignedTinyInteger("roof_other_tahweel_materials")->default(0);
            $table->unsignedTinyInteger("roof_other_tahweel_valve")->default(0);
            $table->text("roof_other_technical_issue")->nullable();

            $table->unsignedTinyInteger("manifold_other_tahweel_materials")->default(0);
            $table->unsignedTinyInteger("manifold_other_tahweel_valve")->default(0);
            $table->unsignedTinyInteger("manifold_sunlight")->default(0);
            $table->unsignedTinyInteger("manifold_insulated")->default(0);

            $table->string("signature", 191);

            $table->unsignedTinyInteger("approved")->default(0);

            $table->text("reason")->nullable(

            );
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
        Schema::dropIfExists('inspection_forms');
    }
}
