<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\OrgEmployeeDepartment;

class CreateOrgEmployeeDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgEmployeeDepartment::table, function (Blueprint $table) {
            $table->primary([OrgEmployeeDepartment::org_department_id, OrgEmployeeDepartment::org_employee_id], "hrm_org_dept_emp_pk");
            $table->integer(OrgEmployeeDepartment::org_department_id)->unsigned();
            $table->integer(OrgEmployeeDepartment::org_employee_id)->unsigned();
            $table->boolean(OrgEmployeeDepartment::is_head)->default(0);

            $table->foreign(OrgEmployeeDepartment::org_employee_id)->references('id')->on(OrgEmployee::table)->onDelete('cascade');
            $table->foreign(OrgEmployeeDepartment::org_department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgEmployeeDepartment::table);
    }
}
