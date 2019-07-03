<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAbsentCount;
use Modules\HrmManagement\Entities\HrmAbsence;
use \Modules\HrmManagement\Entities\HrmLeaveType;

class CreateHrmAbsentCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAbsentCount::table, function (Blueprint $table) {
            $table->primary([HrmAbsentCount::absence_id,HrmAbsentCount::leave_type_id],"hrm_absent_count");
            $table->integer(HrmAbsentCount::absence_id)->unsigned();
            $table->integer(HrmAbsentCount::leave_type_id)->unsigned();
            $table->string(HrmAbsentCount::absent_days, 10);

            $table->foreign(HrmAbsentCount::absence_id)->references('id')->on(HrmAbsence::table)->onDelete('cascade');
            $table->foreign(HrmAbsentCount::leave_type_id)->references('id')->on(HrmLeaveType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAbsentCount::table);
    }
}
