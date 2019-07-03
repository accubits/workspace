<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmTrainingBudget;

class CreateHrmTrainingBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingBudget::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmTrainingBudget::org_id)->unsigned();
            $table->double(HrmTrainingBudget::current_balance,8,2)->unsigned();
            $table->double(HrmTrainingBudget::total_balance,8,2)->unsigned();
            $table->integer(HrmTrainingBudget::creator_user_id)->unsigned();
            $table->integer(HrmTrainingBudget::last_updated_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(HrmTrainingBudget::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmTrainingBudget::last_updated_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(HrmTrainingBudget::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmTrainingBudget::table);
    }
}
