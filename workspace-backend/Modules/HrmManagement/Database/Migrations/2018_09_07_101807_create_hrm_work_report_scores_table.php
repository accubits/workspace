<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmWorkReportScore;

class CreateHrmWorkReportScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReportScore::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmWorkReportScore::slug, 60)->unique();
            $table->string(HrmWorkReportScore::display_name, 60);
            $table->string(HrmWorkReportScore::name, 60);
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
        Schema::dropIfExists(HrmWorkReportScore::table);
    }
}
