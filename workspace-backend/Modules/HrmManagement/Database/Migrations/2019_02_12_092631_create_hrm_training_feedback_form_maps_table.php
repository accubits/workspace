<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackFormMap;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\FormManagement\Entities\FormMaster;


class CreateHrmTrainingFeedbackFormMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingFeedbackFormMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmTrainingFeedbackFormMap::org_id)->unsigned();
            $table->integer(HrmTrainingFeedbackFormMap::hrm_training_request_id)->unsigned();
            $table->integer(HrmTrainingFeedbackFormMap::post_training_form_master_id)->unsigned()->nullable();
            $table->integer(HrmTrainingFeedbackFormMap::post_course_form_master_id)->unsigned()->nullable();

            $table->timestamps();

            $table->foreign(HrmTrainingFeedbackFormMap::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackFormMap::hrm_training_request_id, 'hrm_trainreq_id_fk')->references('id')->on(HrmTrainingRequest::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackFormMap::post_training_form_master_id, 'hrmposttraining_form_id_fk')->references('id')->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackFormMap::post_course_form_master_id, 'hrmpostcourse_form_id_fk')->references('id')->on(FormMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingFeedbackFormMap::table);
    }
}
