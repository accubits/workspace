<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMTaskMap;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLead;

class CreateCRMTaskMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMTaskMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMTaskMap::org_id)->unsigned();
            $table->integer(CRMTaskMap::crm_lead_id)->unsigned();
            $table->integer(CRMTaskMap::task_id)->unsigned();
            $table->integer(CRMTaskMap::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMTaskMap::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMTaskMap::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMTaskMap::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
            $table->foreign(CRMTaskMap::task_id)->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMTaskMap::table);
    }
}
