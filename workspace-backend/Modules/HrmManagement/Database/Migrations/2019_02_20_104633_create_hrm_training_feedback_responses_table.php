<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackFormMap;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\FormManagement\Entities\FormMaster;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackResponse;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\FormManagement\Entities\FormAnswerSheet;

class CreateHrmTrainingFeedbackResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingFeedbackResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmTrainingFeedbackResponse::org_id)->unsigned();
            $table->integer(HrmTrainingFeedbackResponse::training_request_id)->unsigned();
            $table->integer(HrmTrainingFeedbackResponse::form_master_id)->unsigned();
            $table->integer(HrmTrainingFeedbackResponse::employee_id)->unsigned();
            $table->integer(HrmTrainingFeedbackResponse::form_answersheet_id)->unsigned();
            $table->integer(HrmTrainingFeedbackResponse::score)->nullable();
            $table->boolean(HrmTrainingFeedbackResponse::is_final)->default(0);

            $table->timestamps();

            $table->foreign(HrmTrainingFeedbackResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackResponse::training_request_id, 'hrm_trainfeedbackresp_id_fk')->references('id')->on(HrmTrainingRequest::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackResponse::form_master_id, 'hrm_trainfeedback_form_id_fk')->references('id')->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackResponse::employee_id, 'hrm_trainfeedback_emp_id_fk')->references('id')->on(OrgEmployee::table)->onDelete('cascade');
            $table->foreign(HrmTrainingFeedbackResponse::form_answersheet_id, 'hrm_trainfeedback_form_answersheet_id_fk')->references('id')->on(FormAnswerSheet::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingFeedbackResponse::table);
    }
}
