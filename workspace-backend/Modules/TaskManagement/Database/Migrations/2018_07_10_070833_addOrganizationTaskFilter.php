<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\OrgManagement\Entities\Organization;

class AddOrganizationTaskFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn(TaskFilter::table, TaskFilter::org_id))
        {
            Schema::table(TaskFilter::table, function (Blueprint $table) {
                $table->integer(TaskFilter::org_id)
                    ->unsigned()
                    ->nullable()
                    ->after(TaskFilter::user_id);

                $table->foreign(TaskFilter::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
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
        if (Schema::hasColumn(TaskFilter::table, TaskFilter::org_id))
        {
            Schema::table(TaskFilter::table, function (Blueprint $table) {
                $table->dropForeign([TaskFilter::org_id]);
                $table->dropColumn(TaskFilter::org_id);
            });
        }
    }
}
