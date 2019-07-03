<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAppraisalCyclePeriod;

class CreateHrmAppraisalCyclePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalCyclePeriod::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmAppraisalCyclePeriod::period_type, 20)->nullable();
            $table->string(HrmAppraisalCyclePeriod::period_type_display_name, 30)->nullable();
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
        Schema::dropIfExists(HrmAppraisalCyclePeriod::table);
    }
}
