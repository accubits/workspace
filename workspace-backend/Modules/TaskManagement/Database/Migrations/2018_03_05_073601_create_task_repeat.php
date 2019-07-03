<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskRepeat;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;
use Modules\TaskManagement\Entities\TaskRepeatType;

class CreateTaskRepeat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskRepeat::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskRepeat::repeat_every)->unsigned();
            $table->integer(TaskRepeat::task_repeat_type_id)->unsigned();
            $table->integer(TaskRepeat::task_id)->unique()->unsigned();
            $table->integer(TaskRepeat::user_id)->unsigned();
            $table->boolean(TaskRepeat::ends_never)->default(0);
            $table->dateTime(TaskRepeat::ends_on)->nullable();
            $table->integer(TaskRepeat::ends_after)->nullable();
            $table->timestamps();

            $table->foreign(TaskRepeat::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskRepeat::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskRepeat::task_repeat_type_id)->references('id')->on(TaskRepeatType::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskRepeat::table);
    }
}
