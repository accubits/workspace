<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamEventMap;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;

class CreateSocialActivityStreamEventMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamEventMap::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamEventMap::activity_stream_master_id,SocialActivityStreamEventMap::event_id]);
            $table->unsignedInteger(SocialActivityStreamEventMap::activity_stream_master_id);
            $table->unsignedInteger(SocialActivityStreamEventMap::event_id);
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamEventMap::activity_stream_master_id,'asm_event_foreign')->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamEventMap::event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamEventMap::table);
    }
}
