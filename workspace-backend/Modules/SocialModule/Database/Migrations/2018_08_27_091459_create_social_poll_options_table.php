<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialPollOptions;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialPoll;

class CreateSocialPollOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollOptions::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialPollOptions::org_id)->unsigned();
            $table->integer(SocialPollOptions::poll_id)->unsigned();
            $table->integer(SocialPollOptions::creator_user_id)->unsigned();
            $table->string(SocialPollOptions::poll_answeroption, 150)->index();
            
            $table->timestamps();
            
            $table->foreign(SocialPollOptions::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollOptions::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollOptions::poll_id)->references('id')->on(SocialPoll::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPollOptions::table);
    }
}
