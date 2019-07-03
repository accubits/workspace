<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncementComment;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\UserManagement\Entities\User;

class CreateSocialAnnouncementComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncementComment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAnnouncementComment::slug , 60)->unique();
            $table->mediumText(SocialAnnouncementComment::description );
            $table->integer(SocialAnnouncementComment::  parent_announcement_comment_id)->unsigned()->nullable();
            $table->unsignedInteger(SocialAnnouncementComment:: social_announcement_id );
            $table->integer(SocialAnnouncementComment::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialAnnouncementComment:: parent_announcement_comment_id,'pac_id_foreign')->references('id')->on(SocialAnnouncementComment::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementComment::social_announcement_id)->references('id')->on(SocialAnnouncement::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementComment::user_id)->references('id')->on(User::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(SocialAnnouncementComment::table);
        Schema::enableForeignKeyConstraints();
    }
}
