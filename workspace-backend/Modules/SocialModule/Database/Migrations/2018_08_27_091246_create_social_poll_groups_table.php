<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialPollGroup;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialLookup;

class CreateSocialPollGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollGroup::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialPollGroup::slug, 60)->unique();
            $table->integer(SocialPollGroup::org_id)->unsigned();
            $table->string(SocialPollGroup::poll_title,200)->index();
            $table->mediumText(SocialPollGroup::poll_description);
            $table->boolean(SocialPollGroup::is_poll_to_all);
            $table->integer(SocialPollGroup::creator_user_id)->unsigned();
            $table->integer(SocialPollGroup::status_id)->unsigned();

            $table->timestamps();

            $table->foreign(SocialPollGroup::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollGroup::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollGroup::status_id)->references('id')->on(SocialLookup::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPollGroup::table);
    }
}
