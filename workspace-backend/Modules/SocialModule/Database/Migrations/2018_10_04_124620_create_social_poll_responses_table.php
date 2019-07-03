<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialPollResponse;
use Modules\SocialModule\Entities\SocialResponseType;
use Modules\SocialModule\Entities\SocialPollGroup;

class CreateSocialPollResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialPollResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialPollResponse::slug,60)->unique();
            $table->integer(SocialPollResponse::org_id)->unsigned();
            $table->integer(SocialPollResponse::pollgroup_id)->unsigned();
            $table->integer(SocialPollResponse::user_id)->unsigned();
            $table->integer(SocialPollResponse::response_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialPollResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialPollResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialPollResponse::pollgroup_id)->references('id')->on(SocialPollGroup::table)->onDelete('cascade');
            $table->foreign(SocialPollResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialPollResponse::table);
    }
}
