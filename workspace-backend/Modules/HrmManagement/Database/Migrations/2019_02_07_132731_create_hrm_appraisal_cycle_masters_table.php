<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmAppraisalCycleMaster;
use Modules\HrmManagement\Entities\HrmAppraisalCycleApplicable;
use Modules\HrmManagement\Entities\HrmAppraisalCyclePeriod;

class CreateHrmAppraisalCycleMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create(HrmAppraisalCycleMaster::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmAppraisalCycleMaster::slug, 60)->unique();
            $table->integer(HrmAppraisalCycleMaster::org_id)->unsigned();
            $table->string(HrmAppraisalCycleMaster::name, 80);
            $table->mediumText(HrmAppraisalCycleMaster::description)->nullable();
            $table->integer(HrmAppraisalCycleMaster::appraisal_cycle_period_id)->unsigned();
            $table->date(HrmAppraisalCycleMaster::cycle_start_date);
            $table->date(HrmAppraisalCycleMaster::cycle_end_date);
            $table->date(HrmAppraisalCycleMaster::processing_start_date);
            $table->date(HrmAppraisalCycleMaster::processing_end_date);
            $table->integer(HrmAppraisalCycleMaster::applicable_id)->unsigned();
            $table->boolean(HrmAppraisalCycleMaster::review_by_department_head)->default(0);
            $table->boolean(HrmAppraisalCycleMaster::review_by_self)->default(0);
            $table->boolean(HrmAppraisalCycleMaster::review_by_employee)->default(0);
            $table->integer(HrmAppraisalCycleMaster::creator_user_id)->unsigned();
            //$table->boolean(HrmAppraisalCycleMaster::review_by_allemployee)->default(0);
            
            $table->timestamps();

            $table->foreign(HrmAppraisalCycleMaster::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleMaster::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleMaster::appraisal_cycle_period_id)->references('id')->on(HrmAppraisalCyclePeriod::table)->onDelete('cascade');
            $table->foreign(HrmAppraisalCycleMaster::applicable_id)->references('id')->on(HrmAppraisalCycleApplicable::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmAppraisalCycleMaster::table);
    }
}
