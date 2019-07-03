<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialEventResponse;
use Modules\SocialModule\Entities\SocialEvent;

class CreateSocialEventResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEventResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialEventResponse::slug,60)->unique();
            $table->integer(SocialEventResponse::org_id)->unsigned();
            $table->integer(SocialEventResponse::event_id)->unsigned();
            $table->integer(SocialEventResponse::user_id)->unsigned();
            $table->integer(SocialEventResponse::response_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialEventResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialEventResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialEventResponse::event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
            $table->foreign(SocialEventResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialEventResponse::table);
    }
}
