<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialLookup;


class CreateSocialLookup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialLookup::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialLookup::title, 60);
            $table->string(SocialLookup::attribute, 60);
            $table->string(SocialLookup::value, 60);

            $table->unique([SocialLookup::title, SocialLookup::attribute, SocialLookup::value]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialLookup::table);
    }
}
