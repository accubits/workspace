<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamPollMap;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialPollGroup;

class CreateSocialActivityStreamPollMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamPollMap::table, function (Blueprint $table) {

            $table->primary([SocialActivityStreamPollMap::activity_stream_master_id,SocialActivityStreamPollMap::poll_group_id], 'activity_stream_poll_group_id_primary');
            $table->integer(SocialActivityStreamPollMap::activity_stream_master_id)->unsigned();
            $table->integer(SocialActivityStreamPollMap::poll_group_id)->unsigned();

            $table->timestamps();

            $table->foreign(SocialActivityStreamPollMap::activity_stream_master_id)->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamPollMap::poll_group_id)->references('id')->on(SocialPollGroup::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamPollMap::table);
    }
}
