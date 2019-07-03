<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilterCreatedBy;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\UserManagement\Entities\User;

class CreateTaskFilterCreatedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFilterCreatedBy::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskFilterCreatedBy::created_by_id)->unsigned();
            $table->integer(TaskFilterCreatedBy::task_filter_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskFilterCreatedBy::created_by_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskFilterCreatedBy::task_filter_id)->references('id')->on(TaskFilter::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFilterCreatedBy::table);
    }
}
