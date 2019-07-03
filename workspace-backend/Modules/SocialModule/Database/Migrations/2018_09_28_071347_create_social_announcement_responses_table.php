<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncementResponse;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\SocialModule\Entities\SocialResponseType;

class CreateSocialAnnouncementResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncementResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAnnouncementResponse::slug,60)->unique();
            $table->integer(SocialAnnouncementResponse::org_id)->unsigned();
            $table->integer(SocialAnnouncementResponse::annoucement_id)->unsigned();
            $table->integer(SocialAnnouncementResponse::user_id)->unsigned();
            $table->integer(SocialAnnouncementResponse::response_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialAnnouncementResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementResponse::annoucement_id)->references('id')->on(SocialAnnouncement::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAnnouncementResponse::table);
    }
}
