<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmTrainingFeedbackDuration;

class CreateHrmTrainingFeedbackDurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingFeedbackDuration::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmTrainingFeedbackDuration::org_id)->unsigned();
            $table->integer(HrmTrainingFeedbackDuration::duration_in_days)->unsigned();
            $table->timestamps();
            
            $table->foreign(HrmTrainingFeedbackDuration::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingFeedbackDuration::table);
    }
}
