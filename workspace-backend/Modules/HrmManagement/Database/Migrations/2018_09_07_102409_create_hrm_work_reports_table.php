<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmWorkReport;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmWorkReportScore;

class CreateHrmWorkReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReport::table, function (Blueprint $table) {
            $table->increments('id');

            $table->string(HrmWorkReport::slug, 60)->unique();
            $table->string(HrmWorkReport::title, 250);
            $table->integer(HrmWorkReport::org_id)->unsigned();
            $table->integer(HrmWorkReport::superviser_id)->nullable()->unsigned();
            $table->integer(HrmWorkReport::creator_id)->unsigned();
            $table->dateTime(HrmWorkReport::start_date);
            $table->dateTime(HrmWorkReport::end_date);
            $table->dateTime(HrmWorkReport::report_sent_date)->nullable();
            $table->integer(HrmWorkReport::report_score_id)->nullable()->unsigned();
            $table->boolean(HrmWorkReport::is_report_sent)->unsigned()->default(0);
            $table->boolean(HrmWorkReport::is_confirmed)->unsigned()->default(0);

            $table->foreign(HrmWorkReport::superviser_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmWorkReport::creator_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmWorkReport::report_score_id)->references('id')->on(HrmWorkReportScore::table)->onDelete('cascade');
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
        Schema::dropIfExists(HrmWorkReport::table);
    }
}
