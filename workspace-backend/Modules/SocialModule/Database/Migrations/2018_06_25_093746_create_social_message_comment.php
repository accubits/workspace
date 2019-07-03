<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialMessageComment;
use Modules\SocialModule\Entities\SocialMessage;



class CreateSocialMessageComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialMessageComment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialMessageComment::slug, 60)->unique();
            $table->integer(SocialMessageComment::social_message_id)->unsigned();
            $table->integer(SocialMessageComment::parent_social_comment_id)->unsigned()->nullable();
            $table->mediumText(SocialMessageComment::description);
            $table->integer(SocialMessageComment::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialMessageComment::parent_social_comment_id)->references('id')->on(SocialMessageComment::table)->onDelete('cascade');
            $table->foreign(SocialMessageComment::social_message_id)->references('id')->on(SocialMessage::table)->onDelete('cascade');
            $table->foreign(SocialMessageComment::user_id)->references('id')->on(User::table)->onDelete('cascade');

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
        Schema::dropIfExists(SocialMessageComment::table);
        Schema::enableForeignKeyConstraints();
    }
}
