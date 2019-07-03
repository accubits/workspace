<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmTrainingBudget;
use Modules\HrmManagement\Entities\HrmTrainingBudgetHistory;
use Modules\HrmManagement\Entities\HrmTrainingRequest;

class CreateHrmTrainingBudgetHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingBudgetHistory::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmTrainingBudgetHistory::org_id)->unsigned();
            $table->integer(HrmTrainingBudgetHistory::training_budget_id)->unsigned();
            $table->integer(HrmTrainingBudgetHistory::training_request_id)->unsigned();
            $table->double(HrmTrainingBudgetHistory::old_balance,8,2)->unsigned();
            $table->double(HrmTrainingBudgetHistory::new_balance,8,2)->unsigned();
            $table->integer(HrmTrainingBudgetHistory::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(HrmTrainingBudgetHistory::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmTrainingBudgetHistory::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmTrainingBudgetHistory::training_budget_id)->references('id')->on(HrmTrainingBudget::table)->onDelete('cascade');
            $table->foreign(HrmTrainingBudgetHistory::training_request_id)->references('id')->on(HrmTrainingRequest::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingBudgetHistory::table);
    }
}
