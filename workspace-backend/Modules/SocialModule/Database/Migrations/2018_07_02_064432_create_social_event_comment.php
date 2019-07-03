<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialEventComment;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\UserManagement\Entities\User;


class CreateSocialEventComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEventComment::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialEventComment::slug , 60)->unique();
            $table->string(SocialEventComment::description, 200);
            $table->unsignedInteger(SocialEventComment:: parent_event_comment_id )->nullable();
            $table->unsignedInteger(SocialEventComment::social_event_id);
            $table->integer(SocialEventComment::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialEventComment::parent_event_comment_id)->references('id')->on(SocialEventComment::table)->onDelete('cascade');
            $table->foreign(SocialEventComment::social_event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
            $table->foreign(SocialEventComment::user_id)->references('id')->on(User::table)->onDelete('cascade');

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
        Schema::dropIfExists(SocialEventComment::table);
        Schema::enableForeignKeyConstraints();
    }
}
