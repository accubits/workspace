<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\HrmManagement\Entities\HrmWorkReportSettings;
use \Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\HrmManagement\Entities\HrmWorkReportFrequency;

class CreateHrmWorkReportSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmWorkReportSettings::table, function (Blueprint $table) {
            $table->increments('id');

            $table->string(HrmWorkReportSettings::slug, 60)->unique();
            $table->integer(HrmWorkReportSettings::org_id)->unsigned();
            $table->integer(HrmWorkReportSettings::user_id)->nullable()->unsigned();
            $table->integer(HrmWorkReportSettings::department_id)->nullable()->unsigned();
            $table->integer(HrmWorkReportSettings::report_frequency_id)->unsigned();
            $table->integer(HrmWorkReportSettings::monthly_report_day)->nullable();
            $table->string(HrmWorkReportSettings::weekly_report_day, 60)->nullable();

            $table->foreign(HrmWorkReportSettings::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmWorkReportSettings::department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
            $table->foreign(HrmWorkReportSettings::report_frequency_id)->references('id')->on(HrmWorkReportFrequency::table)->onDelete('cascade');
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
        Schema::dropIfExists(HrmWorkReportSettings::table);
    }
}
