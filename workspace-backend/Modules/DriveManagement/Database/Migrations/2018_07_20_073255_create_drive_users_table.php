<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\DriveManagement\Entities\DriveUsers;
use \Modules\UserManagement\Entities\User;
use \Modules\DriveManagement\Entities\DriveContent;

class CreateDriveUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DriveUsers::table, function (Blueprint $table) {
            $table->primary([DriveUsers::user_id, DriveUsers::drive_content_id]);
            $table->unsignedInteger(DriveUsers::user_id);
            $table->unsignedInteger(DriveUsers::drive_content_id);

            $table->foreign(DriveUsers::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(DriveUsers::drive_content_id)->references('id')->on(DriveContent::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DriveUsers::table);
    }
}
