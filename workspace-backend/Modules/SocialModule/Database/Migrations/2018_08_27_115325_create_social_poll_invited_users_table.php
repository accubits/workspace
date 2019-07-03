<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialPollInvitedUsers;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialPollGroup;

class CreateSocialPollInvitedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollInvitedUsers::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialPollInvitedUsers::org_id)->unsigned();
            $table->integer(SocialPollInvitedUsers::poll_group_id)->unsigned();
            $table->integer(SocialPollInvitedUsers::user_id)->unsigned();

            $table->timestamps();

            $table->foreign(SocialPollInvitedUsers::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollInvitedUsers::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollInvitedUsers::poll_group_id)->references('id')->on(SocialPollGroup::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_poll_invited_users');
    }
}
