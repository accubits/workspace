<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\UserInterest;
use Modules\UserManagement\Entities\UserProfile;


class CreateUserInterest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserInterest::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(UserInterest::user_profile_id)->unsigned();
            $table->mediumText(UserInterest::interest_title);

            $table->timestamps();

            $table->foreign(UserInterest::user_profile_id)->references('id')
                    ->on(UserProfile::table)->onDelete('cascade');
                    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('um_user_interest');
    }
}
