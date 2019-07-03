<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\HrmManagement\Entities\HrmAppraisalForEmployee;

class CreateHrmAppraisalForEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalForEmployee::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmAppraisalForEmployee::org_id)->unsigned();
            $table->integer(HrmAppraisalForEmployee::appraisal_cycle_id)->unsigned();
            $table->integer(HrmAppraisalForEmployee::employee_id)->unsigned();

            $table->timestamps();

            $table->foreign(HrmAppraisalForEmployee::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalForEmployee::appraisal_cycle_id, 'hrmaprrevemployee_apr_id')->references('id')->on(HrmAppraisalCycleMaster::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalForEmployee::employee_id, 'hrmaprrevemployee_emp_id')->references('id')->on(OrgEmployee::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAppraisalForEmployee::table);
    }
}
