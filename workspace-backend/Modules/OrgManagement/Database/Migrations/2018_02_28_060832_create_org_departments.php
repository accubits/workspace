<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\OrgManagement\Entities\Organization;

class CreateOrgDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgDepartment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgDepartment::name, 100)->index();
            $table->string(OrgDepartment::slug, 60)->unique();
            $table->integer(OrgDepartment::org_id)->unsigned();
            $table->integer(OrgDepartment::parent_department_id)->unsigned()->nullable();
            $table->integer(OrgDepartment::root_department_id)->unsigned()->nullable();
            $table->string(OrgDepartment::path_enum, 250)->nullable();
            $table->timestamps();

            $table->foreign(OrgDepartment::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(OrgDepartment::parent_department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
            $table->foreign(OrgDepartment::root_department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(OrgDepartment::table);
        Schema::enableForeignKeyConstraints();

    }
}
