<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskRepeatTypeWeekly;
use Modules\TaskManagement\Entities\Task;

class CreateTaskRepeatTypeWeekly extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskRepeatTypeWeekly::table, function (Blueprint $table) {
            $table->increments('id');
            $table->boolean(TaskRepeatTypeWeekly::sunday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::monday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::tuesday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::wednesday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::thursday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::friday)->default(0);
            $table->boolean(TaskRepeatTypeWeekly::saturday)->default(0);
            $table->integer(TaskRepeatTypeWeekly::task_id)->unique()->unsigned();
            $table->timestamps();

            $table->foreign(TaskRepeatTypeWeekly::task_id)->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskRepeatTypeWeekly::table);
    }
}
