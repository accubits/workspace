<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialResponseType;

class CreateSocialResponseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialResponseType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialResponseType::resp_slug,60)->unique();
            $table->string(SocialResponseType::response_text,10)->unique();

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
        Schema::dropIfExists(SocialResponseType::table);
    }
}
