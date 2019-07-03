<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialAppreciation;
use Modules\SocialModule\Entities\SocialAppreciationComment;

class CreateSocialAppreciationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciationComment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAppreciationComment::slug, 60)->unique();
            $table->integer(SocialAppreciationComment::social_appreciation_id)->unsigned();
            $table->integer(SocialAppreciationComment::parent_social_comment_id)->unsigned()->nullable();
            $table->mediumText(SocialAppreciationComment::description);
            $table->integer(SocialAppreciationComment::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialAppreciationComment::parent_social_comment_id)->references('id')->on(SocialAppreciationComment::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationComment::social_appreciation_id)->references('id')->on(SocialAppreciation::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationComment::user_id)->references('id')->on(User::table)->onDelete('cascade');
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
        Schema::dropIfExists(SocialAppreciationComment::table);
        Schema::enableForeignKeyConstraints();
    }
}
