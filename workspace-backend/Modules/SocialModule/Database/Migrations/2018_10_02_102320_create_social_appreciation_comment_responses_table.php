<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialAppreciationComment;
use Modules\SocialModule\Entities\SocialAppreciationCommentResponse;

class CreateSocialAppreciationCommentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciationCommentResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAppreciationCommentResponse::slug, 60)->unique();
            $table->integer(SocialAppreciationCommentResponse::org_id)->unsigned();
            $table->integer(SocialAppreciationCommentResponse::appreciation_comment_id)->unsigned();
            $table->integer(SocialAppreciationCommentResponse::response_type_id)->unsigned();
            $table->integer(SocialAppreciationCommentResponse::user_id)->unsigned();

            $table->timestamps();
            
            $table->foreign(SocialAppreciationCommentResponse::appreciation_comment_id,"apr_cmt_resp_cmt_id")->references('id')->on(SocialAppreciationComment::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationCommentResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationCommentResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationCommentResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAppreciationCommentResponse::table);
    }
}
