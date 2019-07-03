<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLeadAttachment;
use Modules\OrgManagement\Entities\Organization;
use Modules\CRM\Entities\CRMLead;

class CreateCRMLeadAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadAttachment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(CRMLeadAttachment::org_id)->unsigned();
            $table->integer(CRMLeadAttachment::crm_lead_id)->unsigned();
            $table->string(CRMLeadAttachment::attachment_path, 250);
            $table->integer(CRMLeadAttachment::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMLeadAttachment::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLeadAttachment::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLeadAttachment::crm_lead_id)->references('id')->on(CRMLead::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLeadAttachment::table);
    }
}
