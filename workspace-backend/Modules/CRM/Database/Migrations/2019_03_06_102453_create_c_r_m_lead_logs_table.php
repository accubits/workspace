<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadLog;
use Modules\CRM\Entities\CRMLead;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class CreateCRMLeadLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadLog::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMLeadLog::org_id)->unsigned();
            $table->integer(CRMLeadLog::crm_lead_id)->unsigned();
            $table->string(CRMLeadLog::description, 250);
            $table->dateTime(CRMLeadLog::log_date);
            $table->integer(CRMLeadLog::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMLeadLog::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLeadLog::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLeadLog::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLeadLog::table);
    }
}
