<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskComments;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;


class CreateTaskComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskComments::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskComments::slug, 60)->unique();
            $table->string(TaskComments::description, 100);
            $table->integer(TaskComments::parent_comment_id)->unsigned()->nullable();
            $table->integer(TaskComments::task_id)->unsigned();
            $table->integer(TaskComments::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskComments::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskComments::user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskComments::parent_comment_id)->references('id')
                ->on(TaskComments::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskComments::table);
    }
}
