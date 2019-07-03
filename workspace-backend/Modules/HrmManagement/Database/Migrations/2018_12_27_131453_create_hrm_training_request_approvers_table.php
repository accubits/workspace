<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmTrainingRequestApprover;
use Modules\HrmManagement\Entities\HrmTrainingRequest;
use Modules\OrgManagement\Entities\OrgEmployee;

class CreateHrmTrainingRequestApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingRequestApprover::table, function (Blueprint $table) {
            $table->primary([HrmTrainingRequestApprover::training_request_id,HrmTrainingRequestApprover::employee_id],"hrm_training_approver_cpk");
            $table->integer(HrmTrainingRequestApprover::training_request_id)->unsigned();
            $table->integer(HrmTrainingRequestApprover::employee_id)->unsigned();
            $table->boolean(HrmTrainingRequestApprover::has_approved)->default(0);
            $table->timestamps();
            
            $table->foreign(HrmTrainingRequestApprover::employee_id)->references('id')->on(OrgEmployee::table)->onDelete('cascade');
            $table->foreign(HrmTrainingRequestApprover::training_request_id)->references('id')->on(HrmTrainingRequest::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingRequestApprover::table);
    }
}
