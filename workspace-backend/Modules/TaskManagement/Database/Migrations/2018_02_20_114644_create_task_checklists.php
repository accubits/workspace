<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\TaskChecklists;
use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;

class CreateTaskChecklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskChecklists::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskChecklists::slug, 60)->unique();
            $table->string(TaskChecklists::description, 100);
            $table->integer(TaskChecklists::task_id)->unsigned();
            $table->integer(TaskChecklists::user_id)->unsigned();
            $table->boolean(TaskChecklists::checklist_status)->default(TaskChecklists::CHECKLIST_INPROGRESS_STATUS);
            $table->timestamps();

            $table->foreign(TaskChecklists::task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(TaskChecklists::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskChecklists::table);
    }
}
