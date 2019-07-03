<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadForm;
use Modules\OrgManagement\Entities\Organization;
use Modules\FormManagement\Entities\FormMaster;
use Modules\UserManagement\Entities\User;

class CreateCRMLeadFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadForm::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMLeadForm::org_id)->unsigned();
            $table->integer(CRMLeadForm::form_master_id)->unsigned();
            $table->integer(CRMLeadForm::addedby_user_id)->unsigned();
            $table->timestamps();

            $table->foreign(CRMLeadForm::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLeadForm::addedby_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLeadForm::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLeadForm::table);
    }
}
