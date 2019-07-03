<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;

class CreateTaskFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFilter::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskFilter::slug, 60)->unique();
            $table->integer(TaskFilter::org_id)->unsigned();
            $table->string(TaskFilter::title, 60)->unique();
            $table->integer(TaskFilter::user_id)->unsigned();
            $table->boolean(TaskFilter::priority)->default(0);
            $table->boolean(TaskFilter::favourite)->default(0);
            $table->boolean(TaskFilter::is_attachment)->default(0);
            $table->boolean(TaskFilter::is_subtask)->default(0);
            $table->boolean(TaskFilter::is_checklist)->default(0);
            $table->dateTime(TaskFilter::due_date)->nullable();
            $table->dateTime(TaskFilter::start_date)->nullable();
            $table->dateTime(TaskFilter::finished_on)->nullable();
            $table->boolean(TaskFilter::task_status)->default(0);
            $table->boolean(TaskFilter::participant)->default(0);
            $table->boolean(TaskFilter::responsible_person)->default(0);
            $table->boolean(TaskFilter::created_by)->default(0);
            $table->timestamps();

            $table->foreign(TaskFilter::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(TaskFilter::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFilter::table);
    }
}
