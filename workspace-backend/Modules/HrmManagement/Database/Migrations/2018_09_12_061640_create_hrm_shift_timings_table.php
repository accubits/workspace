<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmShiftTimings;

class CreateHrmShiftTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmShiftTimings::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmShiftTimings::slug, 60)->unique();
            $table->time(HrmShiftTimings::start_time);
            $table->time(HrmShiftTimings::end_time);
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
        Schema::dropIfExists(HrmShiftTimings::table);
    }
}
