<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilterTaskStatus;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\TaskManagement\Entities\TaskFilter;

class CreateTaskFilterTaskStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFilterTaskStatus::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskFilterTaskStatus::task_status_id)->unsigned();
            $table->integer(TaskFilterTaskStatus::task_filter_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskFilterTaskStatus::task_status_id)->references('id')->on(TaskStatus::table)->onDelete('cascade');
            $table->foreign(TaskFilterTaskStatus::task_filter_id)->references('id')->on(TaskFilter::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFilterTaskStatus::table);
    }
}
