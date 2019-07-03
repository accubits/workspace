<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\DriveManagement\Entities\DriveType;

class CreateDriveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(DriveType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(DriveType::slug, 60)->unique();
            $table->string(DriveType::name, 60);
            $table->string(DriveType::display_name, 60);
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
        Schema::dropIfExists(DriveType::table);
    }
}
