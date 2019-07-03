<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\Task;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\UserManagement\Entities\User;

class CreateTaskParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskParticipants::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskParticipants::slug, 60)->unique();
            $table->integer(TaskParticipants::task_id)->unsigned();
            $table->integer(TaskParticipants::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskParticipants::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskParticipants::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskParticipants::table);
    }
}
