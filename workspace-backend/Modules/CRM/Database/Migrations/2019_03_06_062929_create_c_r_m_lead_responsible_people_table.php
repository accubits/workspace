<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadResponsiblePerson;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLead;

class CreateCRMLeadResponsiblePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadResponsiblePerson::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMLeadResponsiblePerson::org_id)->unsigned();
            $table->integer(CRMLeadResponsiblePerson::crm_lead_id)->unsigned();
            $table->integer(CRMLeadResponsiblePerson::crm_employee_id)->unsigned();
            $table->integer(CRMLeadResponsiblePerson::addedby_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMLeadResponsiblePerson::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLeadResponsiblePerson::addedby_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLeadResponsiblePerson::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLeadResponsiblePerson::table);
    }
}
