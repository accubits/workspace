<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\DriveManagement\Entities\Drive;
use Modules\OrgManagement\Entities\Organization;
use \Modules\UserManagement\Entities\User;
use \Modules\DriveManagement\Entities\DriveType;

class CreateDrivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Drive::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Drive::slug, 60)->unique();
            $table->integer(Drive::org_id)->unsigned();
            $table->integer(Drive::user_id)->unsigned()->nullable();
            $table->integer(Drive::drive_type_id)->unsigned();
            $table->timestamps();

            $table->foreign(Drive::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(Drive::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(Drive::drive_type_id)->references('id')->on(DriveType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Drive::table);
    }
}
