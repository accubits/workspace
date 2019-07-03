<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\DriveManagement\Entities\DrivePermissionUser;
use \Modules\UserManagement\Entities\User;
use \Modules\DriveManagement\Entities\DrivePermissions;
use \Modules\DriveManagement\Entities\DriveContent;

class CreateDrivePermissionUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DrivePermissionUser::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(DrivePermissionUser::slug, 60)->unique();
            $table->integer(DrivePermissionUser::shared_by)->unsigned();
            $table->integer(DrivePermissionUser::user_id)->unsigned();
            $table->integer(DrivePermissionUser::drive_permission_id)->unsigned();
            $table->integer(DrivePermissionUser::drive_content_id)->unsigned();
            $table->timestamps();

            $table->foreign(DrivePermissionUser::shared_by)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(DrivePermissionUser::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(DrivePermissionUser::drive_permission_id)->references('id')->on(DrivePermissions::table)->onDelete('cascade');
            $table->foreign(DrivePermissionUser::drive_content_id)->references('id')->on(DriveContent::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DrivePermissionUser::table);
    }
}
