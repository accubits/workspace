<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialPoll;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialPollGroup;

class CreateSocialPollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPoll::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialPoll::slug, 60)->unique();
            $table->integer(SocialPoll::org_id)->unsigned();
            $table->integer(SocialPoll::poll_group_id)->unsigned();
            $table->integer(SocialPoll::creator_user_id)->unsigned();
            $table->string(SocialPoll::question, 60)->index();
            $table->boolean(SocialPoll::allow_multiple_choice)->default(0);

            $table->timestamps();
            
            $table->foreign(SocialPoll::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPoll::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPoll::poll_group_id)->references('id')->on(SocialPollGroup::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPoll::table);
    }
}
