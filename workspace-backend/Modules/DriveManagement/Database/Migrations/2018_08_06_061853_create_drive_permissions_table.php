<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\DriveManagement\Entities\DrivePermissions;

class CreateDrivePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DrivePermissions::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(DrivePermissions::slug, 60)->unique();
            $table->string(DrivePermissions::name, 60)->unique();
            $table->string(DrivePermissions::type, 60);
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
        Schema::dropIfExists(DrivePermissions::table);
    }
}
