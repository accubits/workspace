<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;
use Modules\TaskManagement\Entities\TaskStatus;
use Modules\OrgManagement\Entities\Organization;
use Modules\TaskManagement\Entities\TaskScore;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Task::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Task::slug, 60)->unique();
            $table->integer(Task::org_id)->unsigned();
            $table->string(Task::title, 60);
            $table->mediumText(Task::description);
            $table->dateTime(Task::start_date)->nullable();
            $table->dateTime(Task::end_date);
            $table->dateTime(Task::reminder)->nullable();
            $table->boolean(Task::repeat);
            $table->boolean(Task::is_template)->default(0);
            $table->integer(Task::responsible_person_id)->unsigned();
            $table->integer(Task::creator_user_id)->unsigned();
            $table->integer(Task::approver_user_id)->unsigned();
            $table->boolean(Task::responsible_person_time_change)->unsigned()->default(0);
            $table->boolean(Task::approve_task_completed)->unsigned()->default(0);
            $table->boolean(Task::task_completed_user_id)->unsigned()->nullable();
            $table->integer(Task::task_status_id)->unsigned();
            $table->integer(Task::parent_task_id)->unsigned()->nullable();
            $table->integer(Task::task_score_id)->unsigned()->nullable();
            $table->boolean(Task::priority)->unsigned()->default(0);
            $table->boolean(Task::favourite)->unsigned()->default(0);
            $table->boolean(Task::is_to_allemployees)->unsigned()->default(0);
            $table->boolean(Task::archive)->unsigned()->default(0);
            $table->boolean(Task::is_reminder_sent)->unsigned()->default(0);
            $table->string(Task::repeatCronStatus, 60)->nullable();
            $table->timestamps();

            $table->foreign(Task::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(Task::responsible_person_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(Task::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(Task::approver_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(Task::task_status_id)->references('id')->on(TaskStatus::table)->onDelete('cascade');
            $table->foreign(Task::parent_task_id)->references('id')->on(Task::table)->onDelete('cascade');
            $table->foreign(Task::task_score_id)->references('id')->on(TaskScore::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(Task::table);
        Schema::enableForeignKeyConstraints();
    }
}
