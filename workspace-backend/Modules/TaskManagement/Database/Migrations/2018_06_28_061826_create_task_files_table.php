<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFile;
use Modules\TaskManagement\Entities\Task;

class CreateTaskFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFile::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskFile::taskfile_slug, 60)->unique();
            $table->integer(TaskFile::task_id)->unsigned();
            $table->string(TaskFile::filename, 100);
            $table->timestamps();

            $table->foreign(TaskFile::task_id)->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFile::table);
    }
}
