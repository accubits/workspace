<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\HrmManagement\Entities\HrmClock;
use \Modules\UserManagement\Entities\User;
use \Modules\OrgManagement\Entities\Organization;
use \Modules\HrmManagement\Entities\HrmClockStatus;
use \Modules\HrmManagement\Entities\HrmClockMaster;

class CreateHrmClocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmClock::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmClock::slug, 60)->unique();
            $table->integer(HrmClock::org_id)->unsigned();
            $table->integer(HrmClock::user_id)->unsigned();
            $table->dateTime(HrmClock::clock_datetime);
            $table->integer(HrmClock::clock_status_id)->unsigned();
            $table->integer(HrmClock::clock_master_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmClock::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmClock::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmClock::clock_status_id)->references('id')->on(HrmClockStatus::table)->onDelete('cascade');
            $table->foreign(HrmClock::clock_master_id)->references('id')->on(HrmClockMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmClock::table);
    }
}
