<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmLeaveRequests;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\UserManagement\Entities\User;

class CreateHrmLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmLeaveRequests::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmLeaveRequests::slug, 60)->unique();
            $table->integer(HrmLeaveRequests::org_id)->unsigned();
            $table->integer(HrmLeaveRequests::requesting_user_id)->unsigned();
            $table->integer(HrmLeaveRequests::request_to_user_id)->unsigned();
            $table->integer(HrmLeaveRequests::leave_type_id)->unsigned();
            $table->dateTime(HrmLeaveRequests::request_leave_start_date);
            $table->dateTime(HrmLeaveRequests::request_leave_end_date);
            $table->boolean(HrmLeaveRequests::is_request_leave_start_halfday)->unsigned()->default(0);
            $table->boolean(HrmLeaveRequests::is_request_leave_ends_halfday)->unsigned()->default(0);
            $table->boolean(HrmLeaveRequests::is_approved)->unsigned()->default(0);
            $table->boolean(HrmLeaveRequests::is_cancelled)->unsigned()->default(0);
            $table->string(HrmLeaveRequests::reason, 150)->nullable();
            $table->timestamps();

            $table->foreign(HrmLeaveRequests::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmLeaveRequests::leave_type_id)->references('id')->on(HrmLeaveType::table)->onDelete('cascade');
            $table->foreign(HrmLeaveRequests::requesting_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmLeaveRequests::request_to_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmLeaveRequests::table);
    }
}
