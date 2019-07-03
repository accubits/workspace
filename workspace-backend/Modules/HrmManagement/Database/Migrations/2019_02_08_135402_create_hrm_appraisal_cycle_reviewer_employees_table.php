<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAppraisalCycleReviewerEmployee;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;

class CreateHrmAppraisalCycleReviewerEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalCycleReviewerEmployee::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmAppraisalCycleReviewerEmployee::org_id)->unsigned();
            $table->integer(HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id)->unsigned();
            $table->integer(HrmAppraisalCycleReviewerEmployee::employee_id)->unsigned();

            $table->timestamps();

            $table->foreign(HrmAppraisalCycleReviewerEmployee::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleReviewerEmployee::appraisal_cycle_id,'hrmaprrevemployee_apr_id_fk')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleReviewerEmployee::employee_id,'hrmaprrevemployee_emp_id_fk')->references('id')->on(OrgEmployee::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAppraisalCycleReviewerEmployee::table);
    }
}
