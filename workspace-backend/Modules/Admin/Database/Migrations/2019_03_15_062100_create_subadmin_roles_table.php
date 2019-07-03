<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\SubadminRoles;
use Modules\UserManagement\Entities\Roles;

class CreateSubadminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SubadminRoles::table, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger(SubadminRoles::role_id);
            $table->string(SubadminRoles::name, 80)->unique();
            $table->timestamps();

            $table->foreign(SubadminRoles::role_id)->references('id')->on(Roles::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SubadminRoles::table);
    }
}
