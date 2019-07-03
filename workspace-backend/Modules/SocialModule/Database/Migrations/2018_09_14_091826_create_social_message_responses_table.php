<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialMessageResponse;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialResponseType;

class CreateSocialMessageResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialMessageResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialMessageResponse::slug,60)->unique();
            $table->integer(SocialMessageResponse::org_id)->unsigned();
            $table->integer(SocialMessageResponse::message_id)->unsigned();
            $table->integer(SocialMessageResponse::user_id)->unsigned();
            $table->integer(SocialMessageResponse::response_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialMessageResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialMessageResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialMessageResponse::message_id)->references('id')->on(SocialMessage::table)->onDelete('cascade');
            $table->foreign(SocialMessageResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialMessageResponse::table);
    }
}
