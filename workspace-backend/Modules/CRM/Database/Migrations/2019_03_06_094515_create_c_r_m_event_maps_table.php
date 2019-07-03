<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMEventMap;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLead;

class CreateCRMEventMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMEventMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMEventMap::org_id)->unsigned();
            $table->integer(CRMEventMap::crm_lead_id)->unsigned();
            $table->integer(CRMEventMap::event_id)->unsigned();
            $table->integer(CRMEventMap::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMEventMap::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMEventMap::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMEventMap::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
            $table->foreign(CRMEventMap::event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMEventMap::table);
    }
}
