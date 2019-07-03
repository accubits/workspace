<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmLeaveType;
use \Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmLeaveTypeCategory;

class CreateHrmLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmLeaveType::table, function (Blueprint $table) {

            $table->increments('id');
            $table->string(HrmLeaveType::slug, 60)->unique();
            $table->integer(HrmLeaveType::org_id)->unsigned();
            $table->integer(HrmLeaveType::type_category_id)->unsigned();
            $table->string(HrmLeaveType::name, 150);
            $table->string(HrmLeaveType::description, 250);
            $table->integer(HrmLeaveType::creator_id)->unsigned();
            $table->string(HrmLeaveType::period, 50);
            $table->boolean(HrmLeaveType::to_all_employee)->unsigned()->default(0);
            $table->integer(HrmLeaveType::leave_count)->unsigned()->default(0);
            $table->boolean(HrmLeaveType::is_active)->unsigned()->default(0);
            $table->boolean(HrmLeaveType::is_applicable_for)->unsigned()->default(0);
            $table->string(HrmLeaveType::color_code, 60)->nullable();

            $table->foreign(HrmLeaveType::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmLeaveType::type_category_id)->references('id')->on(HrmLeaveTypeCategory::table)->onDelete('cascade');
            $table->foreign(HrmLeaveType::creator_id)->references('id')->on(User::table)->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmLeaveType::table);
    }
}
