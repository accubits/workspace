<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmClockEditHistory;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmClockMaster;

class CreateHrmClockEditHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmClockEditHistory::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmClockEditHistory::slug, 60)->unique();
            $table->integer(HrmClockEditHistory::org_id)->unsigned();
            $table->integer(HrmClockEditHistory::clock_master_id)->unsigned();
            $table->dateTime(HrmClockEditHistory::prev_start_date)->nullable();
            $table->dateTime(HrmClockEditHistory::prev_end_date)->nullable();
            $table->time(HrmClockEditHistory::prev_break_time)->nullable();
            $table->dateTime(HrmClockEditHistory::start_date)->nullable();
            $table->dateTime(HrmClockEditHistory::end_date)->nullable();
            $table->time(HrmClockEditHistory::break_time)->nullable();
            $table->string(HrmClockEditHistory::note, 250)->default(0);
            $table->timestamps();


            $table->foreign(HrmClockEditHistory::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmClockEditHistory::clock_master_id)->references('id')->on(HrmClockMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmClockEditHistory::table);
    }
}
