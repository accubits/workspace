<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmWorkReportEvent;
use \Modules\HrmManagement\Entities\HrmWorkReport;
use \Modules\SocialModule\Entities\SocialEvent;

class CreateHrmWorkReportEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReportEvent::table, function (Blueprint $table) {
            $table->integer(HrmWorkReportEvent::work_report_id)->unsigned();
            $table->integer(HrmWorkReportEvent::event_id)->unsigned();

            $table->foreign(HrmWorkReportEvent::work_report_id)->references('id')->on(HrmWorkReport::table)->onDelete('cascade');
            $table->foreign(HrmWorkReportEvent::event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmWorkReportEvent::table);
    }
}
