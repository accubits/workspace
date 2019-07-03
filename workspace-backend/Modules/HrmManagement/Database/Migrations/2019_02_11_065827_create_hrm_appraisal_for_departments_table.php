<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\HrmManagement\Entities\HrmAppraisalForDepartment;

class CreateHrmAppraisalForDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalForDepartment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmAppraisalForDepartment::org_id)->unsigned();
            $table->integer(HrmAppraisalForDepartment::appraisal_cycle_id)->unsigned();
            $table->integer(HrmAppraisalForDepartment::department_id)->unsigned();

            $table->timestamps();

            $table->foreign(HrmAppraisalForDepartment::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalForDepartment::appraisal_cycle_id,'hrmaprfordept_apr_id_fk')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalForDepartment::department_id,'hrmaprfordept_dept_id_fk')->references('id')->on(OrgDepartment::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAppraisalForDepartment::table);
    }
}
