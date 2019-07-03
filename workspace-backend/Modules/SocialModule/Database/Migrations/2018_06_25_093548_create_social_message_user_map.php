<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialMessageUserMap;


class CreateSocialMessageUserMap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //composit primary key
        Schema::create(SocialMessageUserMap::table, function (Blueprint $table) {
            $table->primary([SocialMessageUserMap::user_id, SocialMessageUserMap::social_message_id]);
            
            $table->unsignedInteger(SocialMessageUserMap::user_id);
            $table->unsignedInteger(SocialMessageUserMap::social_message_id);
            $table->boolean(SocialMessageUserMap::read_status);
            $table->dateTime(SocialMessageUserMap::read_datetime)->nullable();
            $table->timestamps();
            
            $table->foreign(SocialMessageUserMap::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialMessageUserMap::social_message_id)->references('id')->on(SocialMessage::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialMessageUserMap::table);
    }
}
