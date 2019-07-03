<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmWorkReportTask;
use Modules\HrmManagement\Entities\HrmWorkReport;
use Modules\TaskManagement\Entities\Task;

class CreateHrmWorkReportTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReportTask::table, function (Blueprint $table) {
            $table->integer(HrmWorkReportTask::work_report_id)->unsigned();
            $table->integer(HrmWorkReportTask::task_id)->unsigned();

            $table->foreign(HrmWorkReportTask::work_report_id)->references('id')->on(HrmWorkReport::table)->onDelete('cascade');
            $table->foreign(HrmWorkReportTask::task_id)->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmWorkReportTask::table);
    }
}
