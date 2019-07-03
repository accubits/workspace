<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilterResponsiblePersons;
use Modules\UserManagement\Entities\User;
use Modules\TaskManagement\Entities\TaskFilter;

class CreateTaskFilterResponsiblePersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFilterResponsiblePersons::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskFilterResponsiblePersons::responsible_person_id)->unsigned();
            $table->integer(TaskFilterResponsiblePersons::task_filter_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskFilterResponsiblePersons::responsible_person_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskFilterResponsiblePersons::task_filter_id)->references('id')->on(TaskFilter::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFilterResponsiblePersons::table);
    }
}
