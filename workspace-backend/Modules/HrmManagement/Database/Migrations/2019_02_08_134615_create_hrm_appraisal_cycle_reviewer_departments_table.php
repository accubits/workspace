<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmAppraisalCycleReviewerDepartment;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\OrgManagement\Entities\OrgDepartment;

class CreateHrmAppraisalCycleReviewerDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalCycleReviewerDepartment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmAppraisalCycleReviewerDepartment::org_id)->unsigned();
            $table->integer(HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id)->unsigned();
            $table->integer(HrmAppraisalCycleReviewerDepartment::department_id)->unsigned();

            $table->timestamps();

            $table->foreign(HrmAppraisalCycleReviewerDepartment::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleReviewerDepartment::appraisal_cycle_id,'hrmacrdepartment_apr_id_fk')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleReviewerDepartment::department_id,'hrmacrdepartment_dept_id_fk')->references('id')->on(OrgDepartment::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAppraisalCycleReviewerDepartment::table);
    }
}
