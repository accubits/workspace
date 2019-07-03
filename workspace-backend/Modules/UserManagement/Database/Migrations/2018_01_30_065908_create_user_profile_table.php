<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\UserProfileAddress;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserProfile::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(UserProfile::first_name, 100);
            $table->string(UserProfile::last_name, 100)->nullable();
            $table->integer(UserProfile::user_id)->unsigned();
            $table->string(UserProfile::user_image,250 )->nullable();
            $table->string(UserProfile::image_path,250 )->nullable();
            $table->string(UserProfile::phone, 20)->nullable();
            $table->string(UserProfile::timezone, 100)->nullable();
            $table->integer(UserProfile::user_profile_address_id)->nullable()->unsigned();
            $table->date(UserProfile::birth_date )->nullable();
            $table->timestamps();

            $table->foreign(UserProfile::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(UserProfile::user_profile_address_id)->references('id')->on(UserProfileAddress::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(UserProfile::table);
    }
}
