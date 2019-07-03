<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\DriveManagement\Entities\DriveContent;
use Modules\DriveManagement\Entities\Drive;
use Modules\UserManagement\Entities\User;

class CreateDriveContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DriveContent::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(DriveContent::slug, 60)->unique();
            $table->integer(DriveContent::drive_id)->unsigned();
            $table->integer(DriveContent::parent_id)->unsigned()->nullable();
            $table->integer(DriveContent::creator_id)->unsigned();
            $table->string(DriveContent::file_name, 250);
            $table->string(DriveContent::file_path)->nullable();
            $table->string(DriveContent::path_enum)->nullable();
            $table->string(DriveContent::file_extension, 60)->nullable();
            $table->string(DriveContent::size, 60)->nullable();
            $table->boolean(DriveContent::is_folder)->unsigned()->default(0);
/*            $table->boolean(DriveContent::is_mydrive)->unsigned()->default(0);
            $table->boolean(DriveContent::is_companydrive)->unsigned()->default(0);*/
            $table->boolean(DriveContent::is_trashed)->unsigned()->default(0);
            $table->timestamps();

            $table->foreign(DriveContent::drive_id)->references('id')->on(Drive::table)->onDelete('cascade');
            $table->foreign(DriveContent::parent_id)->references('id')->on(DriveContent::table)->onDelete('cascade');
            $table->foreign(DriveContent::creator_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(DriveContent::table);
    }
}
