<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\HrmManagement\Entities\HrmTrainingStatus;

class CreateHrmTrainingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingRequest::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmTrainingRequest::slug,60)->unique();
            $table->integer(HrmTrainingRequest::org_id)->unsigned();
            $table->string(HrmTrainingRequest::name,150);
            $table->mediumText(HrmTrainingRequest::details);
            $table->date(HrmTrainingRequest::starts_on);
            $table->date(HrmTrainingRequest::ends_on);
            $table->integer(HrmTrainingRequest::cost)->nullable();
            $table->integer(HrmTrainingRequest::from_employee_id)->unsigned();
            $table->integer(HrmTrainingRequest::status_id)->unsigned()->nullable();
            $table->boolean(HrmTrainingRequest::in_progress)->default(0);
            $table->boolean(HrmTrainingRequest::is_cancelled)->default(0);
            $table->boolean(HrmTrainingRequest::is_completed)->default(0);
            $table->boolean(HrmTrainingRequest::has_feedback_form)->default(0);
            $table->timestamps();

            $table->foreign(HrmTrainingRequest::from_employee_id)->references('id')->on(OrgEmployee::table)->onDelete('cascade');
            $table->foreign(HrmTrainingRequest::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmTrainingRequest::status_id)->references('id')->on(HrmTrainingStatus::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingRequest::table);
    }
}
