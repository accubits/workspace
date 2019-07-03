<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgEmployeeDesignationDepartment;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgDesignation;

class CreateOrgEmployeeDesignationDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgEmployeeDesignationDepartment::table, function (Blueprint $table) {
            $table->integer(OrgEmployeeDesignationDepartment::org_id)->unsigned();
            $table->integer(OrgEmployeeDesignationDepartment::org_department_id)->unsigned();
            $table->integer(OrgEmployeeDesignationDepartment::org_designation_id)->unsigned();


            $table->foreign(OrgEmployeeDesignationDepartment::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(OrgEmployeeDesignationDepartment::org_department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
            $table->foreign(OrgEmployeeDesignationDepartment::org_designation_id)->references('id')->on(OrgDesignation::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgEmployeeDesignationDepartment::table);
    }
}
