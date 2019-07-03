<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;


class CreateSocialMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialMessage::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialMessage::slug, 60)->unique();
            $table->integer(SocialMessage::org_id)->unsigned();
            $table->string(SocialMessage::title, 60);
            $table->mediumText(SocialMessage::description)->nullable();
            $table->integer(SocialMessage::creator_user_id)->unsigned();
            $table->boolean(SocialMessage::is_message_to_all)->unsigned()->default(0);
            $table->timestamps();

            $table->foreign(SocialMessage::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialMessage::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialMessage::table);
    }
}
