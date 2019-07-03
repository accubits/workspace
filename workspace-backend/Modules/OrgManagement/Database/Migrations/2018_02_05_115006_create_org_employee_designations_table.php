<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgEmployeeDesignation;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDesignation;

class CreateOrgEmployeeDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgEmployeeDesignation::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(OrgEmployeeDesignation::org_employee_id)->unsigned();
            $table->integer(OrgEmployeeDesignation::org_id)->unsigned();
            $table->integer(OrgEmployeeDesignation::org_designation_id)->unsigned();
            $table->boolean(OrgEmployeeDesignation::is_active);

            $table->timestamps();

            $table->foreign(OrgEmployeeDesignation::org_employee_id)->references('id')->on(OrgEmployee::table)->onDelete('cascade');
            $table->foreign(OrgEmployeeDesignation::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(OrgEmployeeDesignation::org_designation_id)->references('id')->on(OrgDesignation::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgEmployeeDesignation::table);
    }
}
