<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAbsence;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmLeaveType;
use Modules\UserManagement\Entities\User;

class CreateHrmAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAbsence::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmAbsence::slug, 60)->unique();
            $table->integer(HrmAbsence::org_id)->unsigned();
            $table->integer(HrmAbsence::user_id)->unsigned();
            $table->integer(HrmAbsence::leave_type_id)->unsigned();
            $table->dateTime(HrmAbsence::absent_start_date_time);
            $table->dateTime(HrmAbsence::absent_end_date_time);
            $table->boolean(HrmAbsence::is_halfday)->unsigned()->default(0);
            $table->string(HrmAbsence::reason, 150);


            $table->foreign(HrmAbsence::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAbsence::leave_type_id)->references('id')->on(HrmLeaveType::table)->onDelete('cascade');
            $table->foreign(HrmAbsence::user_id)->references('id')->on(User::table)->onDelete('cascade');
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
        Schema::dropIfExists(HrmAbsence::table);
    }
}
