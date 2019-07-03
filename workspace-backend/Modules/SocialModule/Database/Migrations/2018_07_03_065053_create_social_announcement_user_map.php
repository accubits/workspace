<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncementUserMap;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialAnnouncement;

class CreateSocialAnnouncementUserMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncementUserMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialAnnouncementUserMap::user_id)->unsigned();
            $table->integer(SocialAnnouncementUserMap::social_announcement_id)->unsigned();
            $table->boolean(SocialAnnouncementUserMap::mark_as_read)->default(0);
            $table->dateTime(SocialAnnouncementUserMap::read_datetime)->nullable();

            $table->timestamps();

            $table->foreign(SocialAnnouncementUserMap::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementUserMap::social_announcement_id)->references('id')->on(SocialAnnouncement::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAnnouncementUserMap::table);
    }
}
