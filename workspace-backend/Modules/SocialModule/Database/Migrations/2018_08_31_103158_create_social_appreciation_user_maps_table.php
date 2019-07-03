<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAppreciationUserMap;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialAppreciation;

class CreateSocialAppreciationUserMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciationUserMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialAppreciationUserMap::appreciation_id)->unsigned();
            $table->integer(SocialAppreciationUserMap::user_id)->unsigned();
            $table->boolean(SocialAppreciationUserMap::mark_as_read);
            $table->dateTime(SocialAppreciationUserMap::read_datetime)->nullable();

            $table->timestamps();

            $table->foreign(SocialAppreciationUserMap::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationUserMap::appreciation_id)->references('id')->on(SocialAppreciation::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAppreciationUserMap::table);
    }
}
