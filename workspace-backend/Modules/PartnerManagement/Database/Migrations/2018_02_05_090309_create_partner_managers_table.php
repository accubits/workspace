<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PartnerManagement\Entities\PartnerManager;
use Modules\UserManagement\Entities\User;

class CreatePartnerManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PartnerManager::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(PartnerManager::partner_manager_slug, 60)->unique();
            $table->string(PartnerManager::name, 100);
            $table->integer(PartnerManager::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(PartnerManager::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(PartnerManager::table);
    }
}
