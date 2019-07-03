<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\UserManagement\Entities\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(User::slug, 60);
            $table->string(User::name, 100);
            $table->string(User::email, 150)->unique();
            $table->string(User::password, 250);
            $table->boolean(User::verified)->unsigned()->default(0);
            $table->rememberToken();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(User::table);
        Schema::enableForeignKeyConstraints();
    }
}
