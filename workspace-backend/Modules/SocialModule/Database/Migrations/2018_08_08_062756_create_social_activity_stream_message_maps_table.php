<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialMessage;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialActivityStreamMessageMap;

class CreateSocialActivityStreamMessageMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamMessageMap::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamMessageMap::activity_stream_master_id,SocialActivityStreamMessageMap::message_id]);
            $table->unsignedInteger(SocialActivityStreamMessageMap::activity_stream_master_id);
            $table->unsignedInteger(SocialActivityStreamMessageMap::message_id);
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamMessageMap::activity_stream_master_id,'asm_msg_foreign')->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamMessageMap::message_id)->references('id')->on(SocialMessage::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamMessageMap::table);
    }
}
