<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PartnerManagement\Entities\Partner;
use Modules\Common\Entities\Country;
use Modules\UserManagement\Entities\User;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Partner::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Partner::partner_slug, 60)->unique();
            $table->string(Partner::name, 100);
            $table->string(Partner::phone, 20)->nullable();
            $table->integer(Partner::user_id)->unsigned();
            $table->integer(Partner::country_id)->unsigned();
            $table->string(Partner::bg_image,200)->nullable();
            $table->string(Partner::bg_image_path,200)->nullable();
            $table->boolean(Partner::is_bg_default_img)->default(0);
            $table->string(Partner::dashboard_msg, 250)->nullable();
            $table->timestamps();

            $table->foreign(Partner::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(Partner::country_id)->references('id')->on(Country::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(Partner::table);
        Schema::enableForeignKeyConstraints();
    }
}
