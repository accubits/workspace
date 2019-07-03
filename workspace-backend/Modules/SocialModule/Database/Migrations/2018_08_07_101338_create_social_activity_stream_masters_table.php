<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\UserManagement\Entities\User;
use Modules\SocialModule\Entities\SocialActivityStreamType;
use Modules\OrgManagement\Entities\Organization;

class CreateSocialActivityStreamMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamMaster::table, function (Blueprint $table) {
            $table->increments('id');

            $table->integer(SocialActivityStreamMaster::org_id)->unsigned();
            $table->integer(SocialActivityStreamMaster::activity_stream_type_id)->unsigned();
            $table->string(SocialActivityStreamMaster::note, 40);
            $table->dateTime(SocialActivityStreamMaster::stream_datetime);
            $table->integer(SocialActivityStreamMaster::from_user_id)->unsigned();
            $table->boolean(SocialActivityStreamMaster::is_hidden)->default(0);
            
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamMaster::from_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamMaster::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamMaster::activity_stream_type_id)->references('id')->on(SocialActivityStreamType::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamMaster::table);
    }
}
