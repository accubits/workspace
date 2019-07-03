<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskRepeatRecurrence;
use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\TaskStatus;

class CreateTaskRepeatRecurrence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskRepeatRecurrence::table, function (Blueprint $table) {

            $table->increments('id');
            $table->integer(TaskRepeatRecurrence::task_id)->unsigned();
            $table->integer(TaskRepeatRecurrence::task_repeat_id)->unsigned();
            $table->integer(TaskRepeatRecurrence::task_status_id)->unsigned();
            $table->dateTime(TaskRepeatRecurrence::start_date)->nullable();
            $table->dateTime(TaskRepeatRecurrence::end_date)->nullable();
            $table->dateTime(TaskRepeatRecurrence::task_repeat_date)->nullable();
            $table->timestamps();

            $table->foreign(TaskRepeatRecurrence::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskRepeatRecurrence::task_repeat_id)->references('id')->on(TaskRepeat::table)->onDelete('cascade');
            $table->foreign(TaskRepeatRecurrence::task_status_id)->references('id')->on(TaskStatus::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskRepeatRecurrence::table);
    }
}
