<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamAnnouncementMap;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialAnnouncement;

class CreateSocialActivityStreamAnnouncementMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamAnnouncementMap::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamAnnouncementMap::activity_stream_master_id,SocialActivityStreamAnnouncementMap::annoucement_id], 'activity_stream_announcement_primary');
            $table->unsignedInteger(SocialActivityStreamAnnouncementMap::activity_stream_master_id);
            $table->unsignedInteger(SocialActivityStreamAnnouncementMap::annoucement_id);
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamAnnouncementMap::activity_stream_master_id,'asm_anc_foreign')->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamAnnouncementMap::annoucement_id)->references('id')->on(SocialAnnouncement::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamAnnouncementMap::table);
    }
}
