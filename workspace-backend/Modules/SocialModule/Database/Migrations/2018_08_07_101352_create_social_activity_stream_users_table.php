<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamUser;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;

class CreateSocialActivityStreamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamUser::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamUser::activity_stream_master_id, SocialActivityStreamUser::to_user_id]);

            $table->integer(SocialActivityStreamUser::activity_stream_master_id)->unsigned();
            $table->integer(SocialActivityStreamUser::to_user_id)->unsigned();
            $table->timestamps();

            $table->foreign(SocialActivityStreamUser::to_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamUser::activity_stream_master_id)->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamUser::table);
    }
}
