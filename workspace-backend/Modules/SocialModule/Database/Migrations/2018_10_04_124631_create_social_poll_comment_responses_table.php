<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialPollComment;
use Modules\SocialModule\Entities\SocialPollCommentResponse;

class CreateSocialPollCommentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollCommentResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialPollCommentResponse::slug, 60)->unique();
            $table->integer(SocialPollCommentResponse::org_id)->unsigned();
            $table->integer(SocialPollCommentResponse::pollgroup_comment_id)->unsigned();
            $table->integer(SocialPollCommentResponse::response_type_id)->unsigned();
            $table->integer(SocialPollCommentResponse::user_id)->unsigned();

            $table->timestamps();

            $table->foreign(SocialPollCommentResponse::pollgroup_comment_id)->references('id')->on(SocialPollComment::table)->onDelete('cascade');
            $table->foreign(SocialPollCommentResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollCommentResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollCommentResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPollCommentResponse::table);
    }
}
