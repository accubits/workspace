<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAnnouncementCommentResponse;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialAnnouncementComment;

class CreateSocialAnnouncementCommentResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAnnouncementCommentResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAnnouncementCommentResponse::slug, 60)->unique();
            $table->integer(SocialAnnouncementCommentResponse::org_id)->unsigned();
            $table->integer(SocialAnnouncementCommentResponse::anc_comment_id)->unsigned();
            $table->integer(SocialAnnouncementCommentResponse::response_type_id)->unsigned();
            $table->integer(SocialAnnouncementCommentResponse::user_id)->unsigned();

            $table->timestamps();
            
            $table->foreign(SocialAnnouncementCommentResponse::anc_comment_id)->references('id')->on(SocialAnnouncementComment::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementCommentResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementCommentResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAnnouncementCommentResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAnnouncementCommentResponse::table);
    }
}
