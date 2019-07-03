<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskFilterParticipants;
use Modules\TaskManagement\Entities\TaskParticipants;
use Modules\TaskManagement\Entities\TaskFilter;
use Modules\UserManagement\Entities\User;

class CreateTaskFilterParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskFilterParticipants::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskFilterParticipants::participant_id)->unsigned();
            $table->integer(TaskFilterParticipants::task_filter_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskFilterParticipants::participant_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(TaskFilterParticipants::task_filter_id)->references('id')->on(TaskFilter::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskFilterParticipants::table);
    }
}
