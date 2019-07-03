<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialPollUserAnswers;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialPoll;
use Modules\SocialModule\Entities\SocialPollOptions;

class CreateSocialPollUserAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollUserAnswers::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialPollUserAnswers::org_id)->unsigned();
            $table->integer(SocialPollUserAnswers::poll_id)->unsigned();
            $table->integer(SocialPollUserAnswers::poll_option_id)->unsigned();
            $table->integer(SocialPollUserAnswers::user_id)->unsigned();
            
            $table->timestamps();
            
            $table->foreign(SocialPollUserAnswers::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollUserAnswers::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollUserAnswers::poll_id)->references('id')->on(SocialPoll::table)->onDelete('cascade');
            $table->foreign(SocialPollUserAnswers::poll_option_id)->references('id')->on(SocialPollOptions::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPollUserAnswers::table);
    }
}
