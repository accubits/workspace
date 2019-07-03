<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployeeStatus;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseMapping;

class CreateOrgEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgEmployee::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgEmployee::name, 100)->index();
            $table->string(OrgEmployee::slug, 60);
            $table->integer(OrgEmployee::user_id)->unsigned();
            $table->integer(OrgEmployee::org_id)->unsigned();
            $table->integer(OrgEmployee::employee_status_id)->unsigned();
            //reporting manager should be
            $table->integer(OrgEmployee::reporting_manager_id)->nullable()->unsigned();
            $table->integer(OrgEmployee::org_license_id)->unsigned();
            $table->integer(OrgEmployee::org_license_map_id)->unsigned()->nullable();
            $table->date(OrgEmployee::joining_date);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign(OrgEmployee::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(OrgEmployee::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(OrgEmployee::employee_status_id)->references('id')->on(OrgEmployeeStatus::table)->onDelete('cascade');
            $table->foreign(OrgEmployee::org_license_id)->references('id')->on(OrgLicense::table)->onDelete('cascade');
            $table->foreign(OrgEmployee::org_license_map_id)->references('id')->on(OrgLicenseMapping::table)->onDelete('cascade');
            $table->foreign(OrgEmployee::reporting_manager_id)->references('id')->on(OrgEmployee::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgEmployee::table);
    }
}
