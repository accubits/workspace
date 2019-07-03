<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadNote;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLead;

class CreateCRMLeadNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadNote::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(CRMLeadNote::slug, 60)->unique();
            $table->integer(CRMLeadNote::org_id)->unsigned();
            $table->integer(CRMLeadNote::crm_lead_id)->unsigned();
            $table->string(CRMLeadNote::description, 250);
            $table->integer(CRMLeadNote::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMLeadNote::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLeadNote::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLeadNote::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLeadNote::table);
    }
}
