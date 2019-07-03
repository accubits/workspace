<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskRepeatMap;
use Modules\TaskManagement\Entities\Task;

class CreateTaskRepeatMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskRepeatMap::table, function (Blueprint $table) {
            $table->integer(TaskRepeatMap::origin_task_id)->unsigned();
            $table->integer(TaskRepeatMap::task_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskRepeatMap::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskRepeatMap::origin_task_id)->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskRepeatMap::table);
    }
}
