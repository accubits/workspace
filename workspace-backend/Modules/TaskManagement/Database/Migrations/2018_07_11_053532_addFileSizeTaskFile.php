<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFile;

class AddFileSizeTaskFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(TaskFile::table, function (Blueprint $table) {
            $table->string(TaskFile::filesize, 30)
                ->nullable()
                ->after(TaskFile::filename);

            $table->string(TaskFile::extension, 30)
                ->nullable()
                ->after(TaskFile::filename);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(TaskFile::table, function (Blueprint $table) {
            $table->dropColumn(TaskFile::filesize);
            $table->dropColumn(TaskFile::extension);
        });


    }
}
