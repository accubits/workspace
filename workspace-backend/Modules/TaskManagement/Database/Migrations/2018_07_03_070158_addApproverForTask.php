<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\Task;
use Modules\UserManagement\Entities\User;

class AddApproverForTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn(Task::table, Task::approver_user_id))
        {
            Schema::table(Task::table, function (Blueprint $table) {
                $table->integer(Task::approver_user_id)
                    ->unsigned()
                    ->nullable()
                    ->after(Task::creator_user_id);

                $table->foreign(Task::approver_user_id)->references('id')->on(User::table)->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn(Task::table, Task::approver_user_id))
        {
            Schema::table(Task::table, function (Blueprint $table) {
                $table->dropForeign([Task::approver_user_id]);
                $table->dropColumn(Task::approver_user_id);
            });
        }

    }
}
