<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialEventMember;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialEvent;



class CreateSocialEventMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEventMember::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(SocialEventMember::user_id)->unsigned();
            $table->integer(SocialEventMember::social_event_id)->unsigned();
            $table->string(SocialEventMember::response_status, 10);

            $table->timestamps();

            $table->foreign(SocialEventMember::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialEventMember::social_event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_event_member');
    }
}
