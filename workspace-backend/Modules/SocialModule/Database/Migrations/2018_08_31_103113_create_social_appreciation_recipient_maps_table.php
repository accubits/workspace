<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAppreciationRecipientMap;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialAppreciation;

class CreateSocialAppreciationRecipientMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciationRecipientMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialAppreciationRecipientMap::appreciation_id)->unsigned();
            $table->integer(SocialAppreciationRecipientMap::user_id)->unsigned();
            $table->boolean(SocialAppreciationRecipientMap::mark_as_read);
            $table->dateTime(SocialAppreciationRecipientMap::read_datetime)->nullable();

            $table->timestamps();

            $table->foreign(SocialAppreciationRecipientMap::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAppreciationRecipientMap::appreciation_id)->references('id')->on(SocialAppreciation::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAppreciationRecipientMap::table);
    }
}
