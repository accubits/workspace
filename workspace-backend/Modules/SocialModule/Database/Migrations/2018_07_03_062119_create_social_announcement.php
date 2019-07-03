<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncement;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class CreateSocialAnnouncement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncement::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAnnouncement::slug, 60)->unique();
            $table->string(SocialAnnouncement::title, 60);
            $table->mediumText(SocialAnnouncement::description);
            $table->integer(SocialAnnouncement::org_id)->unsigned();
            $table->integer(SocialAnnouncement::creator_user_id)->unsigned();
            $table->boolean(SocialAnnouncement::is_announcement_to_all)->unsigned()->default(0);
            $table->timestamps();

            $table->foreign(SocialAnnouncement::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncement::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAnnouncement::table);
    }
}
