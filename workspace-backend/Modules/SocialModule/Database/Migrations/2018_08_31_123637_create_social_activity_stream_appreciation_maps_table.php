<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamAppreciationMap;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\SocialModule\Entities\SocialAppreciation;

class CreateSocialActivityStreamAppreciationMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamAppreciationMap::table, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger(SocialActivityStreamAppreciationMap::activity_stream_master_id);
            $table->unsignedInteger(SocialActivityStreamAppreciationMap::appreciation_id);
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamAppreciationMap::activity_stream_master_id,'asm_appreciation_foreign')->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamAppreciationMap::appreciation_id)->references('id')->on(SocialAppreciation::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamAppreciationMap::table);
    }
}
