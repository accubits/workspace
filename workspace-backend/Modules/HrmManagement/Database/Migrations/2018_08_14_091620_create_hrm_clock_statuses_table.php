<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\HrmManagement\Entities\HrmClockStatus;

class CreateHrmClockStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmClockStatus::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmClockStatus::slug, 60)->unique();
            $table->string(HrmClockStatus::name, 60);
            $table->string(HrmClockStatus::display_name, 60);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmClockStatus::table);
    }
}
