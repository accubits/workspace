<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\UserProfileInterest;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Entities\Interest;

class CreateUserProfileInterest extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create(UserProfileInterest::table, function (Blueprint $table) {
            $table->primary([UserProfileInterest::user_profile_id ,UserProfileInterest::user_interest_id],'for migrations');

            $table->string(UserProfileInterest::slug, 60)->unique();
            $table->unsignedInteger(UserProfileInterest::user_profile_id);
            $table->unsignedInteger(UserProfileInterest::user_interest_id);

            

            $table->timestamps();

            $table->foreign(UserProfileInterest::user_profile_id)->references('id')->on(UserProfile::table)->onDelete('cascade');
            $table->foreign(UserProfileInterest::user_interest_id)->references('id')->on(Interest::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() 
    {
        Schema::dropIfExists('um_user_profile_interest');
    }
}
