<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialAppreciation;
use Modules\SocialModule\Entities\SocialAppreciationResponse;
use Modules\SocialModule\Entities\SocialResponseType;
class CreateSocialAppreciationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciationResponse::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAppreciationResponse::slug,60)->unique();
            $table->integer(SocialAppreciationResponse::org_id)->unsigned();
            $table->integer(SocialAppreciationResponse::appreciation_id)->unsigned();
            $table->integer(SocialAppreciationResponse::user_id)->unsigned();
            $table->integer(SocialAppreciationResponse::response_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialAppreciationResponse::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationResponse::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationResponse::appreciation_id)->references('id')->on(SocialAppreciation::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationResponse::response_type_id)->references('id')->on(SocialResponseType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAppreciationResponse::table);
    }
}
