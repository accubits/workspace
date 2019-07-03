<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskStatusLog;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;
use Modules\TaskManagement\Entities\TaskStatus;

class CreateTaskStatusLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskStatusLog::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskStatusLog::task_id)->unsigned();
            $table->integer(TaskStatusLog::user_id)->unsigned();
            $table->integer(TaskStatusLog::previous_status_id)->unsigned()->nullable();
            $table->integer(TaskStatusLog::current_status_id)->unsigned();
            $table->dateTime(TaskStatusLog::status_log_time);
            $table->timestamps();

            $table->foreign(TaskStatusLog::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskStatusLog::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskStatusLog::previous_status_id)->references('id')->on(TaskStatus::table)->onDelete('cascade');
            $table->foreign(TaskStatusLog::current_status_id)->references('id')->on(TaskStatus::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskStatusLog::table);
    }
}
