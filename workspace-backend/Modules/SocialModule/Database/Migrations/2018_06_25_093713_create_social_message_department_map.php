<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialMessageDepartmentMap;




class CreateSocialMessageDepartmentMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
    Schema::create(SocialMessageDepartmentMap::table, function (Blueprint $table) {
        
        $table->primary([SocialMessageDepartmentMap::department_id, SocialMessageDepartmentMap::social_message_id], 'liya');   
        $table->unsignedInteger(SocialMessageDepartmentMap::department_id);
        $table->unsignedInteger(SocialMessageDepartmentMap::social_message_id);

        $table->foreign(SocialMessageDepartmentMap::department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
        $table->foreign(SocialMessageDepartmentMap::social_message_id)->references('id')->on(SocialMessage::table)->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialMessageDepartmentMap::table);
    }
}
