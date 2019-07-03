<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmClockMaster;
use \Modules\UserManagement\Entities\User;
use \Modules\OrgManagement\Entities\Organization;

class CreateHrmClockMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmClockMaster::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmClockMaster::slug, 60)->unique();
            $table->integer(HrmClockMaster::org_id)->unsigned();
            $table->integer(HrmClockMaster::user_id)->unsigned();
            $table->dateTime(HrmClockMaster::start_date);
            $table->dateTime(HrmClockMaster::stop_date)->nullable();
            $table->time(HrmClockMaster::total_break_time)->nullable();
            $table->time(HrmClockMaster::total_working_time)->nullable();
            $table->string(HrmClockMaster::last_recorded_time, 60)->nullable();
            $table->string(HrmClockMaster::note, 250);
            $table->timestamps();

            $table->foreign(HrmClockMaster::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmClockMaster::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmClockMaster::table);
    }
}
