<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamFormMap;
use Modules\FormManagement\Entities\FormMaster;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;

class CreateSocialActivityStreamFormMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamFormMap::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamFormMap::activity_stream_master_id,SocialActivityStreamFormMap::form_master_id],"asm_form_primary");
            $table->integer(SocialActivityStreamFormMap::activity_stream_master_id)->unsigned();
            $table->integer(SocialActivityStreamFormMap::form_master_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamFormMap::activity_stream_master_id,'asm_form_foreign')->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamFormMap::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamFormMap::table);
    }
}
