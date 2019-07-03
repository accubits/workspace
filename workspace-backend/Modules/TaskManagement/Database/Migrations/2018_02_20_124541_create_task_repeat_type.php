<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskRepeatType;

class CreateTaskRepeatType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskRepeatType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Task::title, 60);
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
        Schema::dropIfExists(TaskRepeatType::table);
    }
}
