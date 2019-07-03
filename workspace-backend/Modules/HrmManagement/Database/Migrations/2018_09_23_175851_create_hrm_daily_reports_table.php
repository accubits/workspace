<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmDailyReport;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmClockMaster;

class CreateHrmDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmDailyReport::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmDailyReport::slug, 60)->unique();
            $table->integer(HrmDailyReport::clock_master_id)->unsigned();
            $table->integer(HrmDailyReport::creator_id)->unsigned();
            $table->integer(HrmDailyReport::supervisor_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmDailyReport::creator_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmDailyReport::supervisor_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmDailyReport::clock_master_id)->references('id')->on(HrmClockMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmDailyReport::table);
    }
}
