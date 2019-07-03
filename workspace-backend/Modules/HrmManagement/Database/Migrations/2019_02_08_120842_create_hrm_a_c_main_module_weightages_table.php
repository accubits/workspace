<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmACMainModuleWeightage;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\HrmManagement\Entities\HrmAppraisalMainModule;
use Modules\OrgManagement\Entities\Organization;

class CreateHrmACMainModuleWeightagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmACMainModuleWeightage::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmACMainModuleWeightage::org_id)->unsigned();
            $table->integer(HrmACMainModuleWeightage::appraisal_cycle_id)->unsigned();
            $table->integer(HrmACMainModuleWeightage::appraisal_main_module_id)->unsigned();
            $table->integer(HrmACMainModuleWeightage::score_percent)->unsigned();

            $table->timestamps();

            $table->foreign(HrmACMainModuleWeightage::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmACMainModuleWeightage::appraisal_cycle_id,'hrmacmainmoduleweightage_ac_id_fk')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmACMainModuleWeightage::appraisal_main_module_id,'hrmacmainmoduleweightage_mm_id_fk')->references('id')->on(HrmAppraisalMainModule::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmACMainModuleWeightage::table);
    }
}
