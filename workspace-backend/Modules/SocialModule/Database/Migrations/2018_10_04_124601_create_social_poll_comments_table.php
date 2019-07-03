<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialPollComment;
use Modules\SocialModule\Entities\SocialPollGroup;

class CreateSocialPollCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollComment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialPollComment::slug, 60)->unique();
            $table->integer(SocialPollComment::social_pollgroup_id)->unsigned();
            $table->integer(SocialPollComment::parent_social_comment_id)->unsigned()->nullable();
            $table->mediumText(SocialPollComment::description);
            $table->integer(SocialPollComment::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialPollComment::parent_social_comment_id)->references('id')->on(SocialPollComment::table)->onDelete('cascade');
            $table->foreign(SocialPollComment::social_pollgroup_id)->references('id')->on(SocialPollGroup::table)->onDelete('cascade');
            $table->foreign(SocialPollComment::user_id)->references('id')->on(User::table)->onDelete('cascade');
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
        Schema::dropIfExists(SocialPollComment::table);
        Schema::enableForeignKeyConstraints();
    }
}
