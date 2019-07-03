<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncementDepartmentMap;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\SocialModule\Entities\SocialAnnouncement;

class CreateSocialAnnouncementDepartmentMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncementDepartmentMap::table, function (Blueprint $table) {
            $table->primary([SocialAnnouncementDepartmentMap::department_id , SocialAnnouncementDepartmentMap::social_announcement_id],'for migrations');
            
            $table->unsignedInteger(SocialAnnouncementDepartmentMap::department_id);
            $table->unsignedInteger(SocialAnnouncementDepartmentMap::social_announcement_id);
            
            $table->timestamps();

            $table->foreign(SocialAnnouncementDepartmentMap::department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementDepartmentMap::social_announcement_id, 'sa_id_foreign')->references('id')->on(SocialAnnouncement::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAnnouncementDepartmentMap::table);
    }
}
