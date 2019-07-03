<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class CreateHrmKraModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmKraModule::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmKraModule::slug, 60)->unique();
            $table->integer(HrmKraModule::org_id)->unsigned();
            $table->string(HrmKraModule::title, 60);
            $table->mediumText(HrmKraModule::description)->nullable();
            $table->integer(HrmKraModule::creator_user_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmKraModule::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmKraModule::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmKraModule::table);
    }
}
