<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\HrmManagement\Entities\HrmAcKraModuleWeightage;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;

class CreateHrmAcKraModuleWeightagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAcKraModuleWeightage::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmAcKraModuleWeightage::org_id)->unsigned();
            $table->integer(HrmAcKraModuleWeightage::appraisal_cycle_id)->unsigned();
            $table->integer(HrmAcKraModuleWeightage::kra_module_id)->unsigned();
            $table->integer(HrmAcKraModuleWeightage::score_percent)->unsigned();

            $table->timestamps();

            $table->foreign(HrmAcKraModuleWeightage::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAcKraModuleWeightage::appraisal_cycle_id,'hrmackramodule_apr_id_fk')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmAcKraModuleWeightage::kra_module_id,'hrmackramodule_kra_id_fk')->references('id')->on(HrmKraModule::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAcKraModuleWeightage::table);
    }
}
