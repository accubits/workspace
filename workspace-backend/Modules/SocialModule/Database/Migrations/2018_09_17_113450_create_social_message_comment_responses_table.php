<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialMessageCommentResponse;
use Modules\SocialModule\Entities\SocialMessageComment;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;

class CreateSocialMessageCommentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialMessageCommentResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialMessageCommentResponse::slug, 60)->unique();
            $table->integer(SocialMessageCommentResponse::org_id)->unsigned();
            $table->integer(SocialMessageCommentResponse::message_comment_id)->unsigned();
            $table->integer(SocialMessageCommentResponse::response_type_id)->unsigned();
            $table->integer(SocialMessageCommentResponse::user_id)->unsigned();

            $table->timestamps();
            
            $table->foreign(SocialMessageCommentResponse::message_comment_id)->references('id')->on(SocialMessageComment::table)->onDelete('cascade');
            $table->foreign(SocialMessageCommentResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialMessageCommentResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialMessageCommentResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialMessageCommentResponse::table);
    }
}
