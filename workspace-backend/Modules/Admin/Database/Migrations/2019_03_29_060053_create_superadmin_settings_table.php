<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\SuperadminSettings;
use Modules\UserManagement\Entities\User;

class CreateSuperadminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SuperadminSettings::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SuperadminSettings::slug, 60);
            $table->integer(SuperadminSettings::user_id)->unsigned();
            $table->boolean(SuperadminSettings::is_default_dashboard_img)->default(0);
            $table->string(SuperadminSettings::dashboard_img,200)->nullable();
            $table->string(SuperadminSettings::dashboard_img_path,200)->nullable();
            $table->string(SuperadminSettings::dashboard_msg, 250)->nullable();
            $table->timestamps();

            $table->foreign(SuperadminSettings::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SuperadminSettings::table);
    }
}
