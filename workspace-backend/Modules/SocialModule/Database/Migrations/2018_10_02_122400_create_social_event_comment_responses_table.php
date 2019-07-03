<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialEventComment;
use Modules\SocialModule\Entities\SocialEventCommentResponse;

class CreateSocialEventCommentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEventCommentResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialEventCommentResponse::slug, 60)->unique();
            $table->integer(SocialEventCommentResponse::org_id)->unsigned();
            $table->integer(SocialEventCommentResponse::event_comment_id)->unsigned();
            $table->integer(SocialEventCommentResponse::response_type_id)->unsigned();
            $table->integer(SocialEventCommentResponse::user_id)->unsigned();

            $table->timestamps();
            
            $table->foreign(SocialEventCommentResponse::event_comment_id)->references('id')->on(SocialEventComment::table)->onDelete('cascade');
            $table->foreign(SocialEventCommentResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialEventCommentResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialEventCommentResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialEventCommentResponse::table);
    }
}
