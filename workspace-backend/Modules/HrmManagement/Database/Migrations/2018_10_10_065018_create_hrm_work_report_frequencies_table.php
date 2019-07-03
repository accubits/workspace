<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\HrmManagement\Entities\HrmWorkReportFrequency;

class CreateHrmWorkReportFrequenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReportFrequency::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmWorkReportFrequency::slug, 60)->unique();
            $table->string(HrmWorkReportFrequency::frequency_display_name, 150);
            $table->string(HrmWorkReportFrequency::frequency_name, 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmWorkReportFrequency::table);
    }
}
