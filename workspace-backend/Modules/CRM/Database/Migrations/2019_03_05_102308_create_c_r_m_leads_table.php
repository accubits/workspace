<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\CRM\Entities\CRMLead;
use Modules\OrgManagement\Entities\Organization;
use Modules\CRM\Entities\CRMLeadStatus;
use Modules\CRM\Entities\CRMLeadUserType;

class CreateCRMLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLead::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(CRMLead::slug, 60)->unique();
            $table->integer(CRMLead::org_id)->unsigned();
            $table->string(CRMLead::name, 80);
            $table->integer(CRMLead::lead_user_type_id)->unsigned();
            $table->date(CRMLead::date_of_birth)->nullable();
            $table->string(CRMLead::email, 80)->nullable();
            $table->string(CRMLead::phone, 15)->nullable();
            $table->integer(CRMLead::lead_status_id)->unsigned();
            $table->string(CRMLead::image_path, 250)->nullable();
            $table->integer(CRMLead::creator_user_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(CRMLead::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(CRMLead::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(CRMLead::lead_status_id)->references('id')->on(CRMLeadStatus::table)->onDelete('cascade');
            $table->foreign(CRMLead::lead_user_type_id)->references('id')->on(CRMLeadUserType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CRMLead::table);
    }
}
