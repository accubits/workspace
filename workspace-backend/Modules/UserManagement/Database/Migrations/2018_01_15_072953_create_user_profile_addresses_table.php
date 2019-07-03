<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\UserProfileAddress;
use Modules\Common\Entities\Country;

class CreateUserProfileAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(UserProfileAddress::table, function (Blueprint $table) {
            $table->increments('id');

            $table->string(UserProfileAddress::street_address,150)->index();
            $table->string(UserProfileAddress::address_line2,150)->index();
            $table->string(UserProfileAddress::city,80)->index();
            $table->string(UserProfileAddress::state,80)->index();
            $table->integer(UserProfileAddress::country_id)->unsigned()->nullable();
            $table->string(UserProfileAddress::zip_code, 10)->index();

            $table->timestamps();

            $table->foreign(UserProfileAddress::country_id)->references('id')->on(Country::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(UserProfileAddress::table);
    }
}
