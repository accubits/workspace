<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\Task;
use Modules\OrgManagement\Entities\Organization;

class AddOrganizationIdToTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn(Task::table, Task::org_id))
        {
            Schema::table(Task::table, function (Blueprint $table) {
                $table->integer(Task::org_id)
                    ->unsigned()
                    ->nullable()
                    ->after(Task::slug);
            });

            Schema::table(Task::table, function (Blueprint $table) {
                $table->foreign(Task::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
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
        if (Schema::hasColumn(Task::table, Task::org_id)) {
            Schema::table(Task::table, function (Blueprint $table) {
                $table->dropForeign([Task::org_id]);
                $table->dropColumn(Task::org_id);
            });
        }
    }
}
