<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmLeaveTypeUserMapping;
use \Modules\OrgManagement\Entities\Organization;
use \Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\UserManagement\Entities\User;

class CreateHrmLeaveTypeUserMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmLeaveTypeUserMapping::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmLeaveTypeUserMapping::org_id)->unsigned();
            $table->integer(HrmLeaveTypeUserMapping::leave_type_id)->unsigned();
            $table->integer(HrmLeaveTypeUserMapping::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmLeaveTypeUserMapping::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmLeaveTypeUserMapping::leave_type_id)->references('id')->on(HrmLeaveType::table)->onDelete('cascade');
            $table->foreign(HrmLeaveTypeUserMapping::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmLeaveTypeUserMapping::table);
    }
}
