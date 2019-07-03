<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAppraisalMainModule;

class CreateHrmAppraisalMainModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalMainModule::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmAppraisalMainModule::module_name, 30);
            $table->string(HrmAppraisalMainModule::module_display_name, 40);
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
        Schema::dropIfExists(HrmAppraisalMainModule::table);
    }
}
