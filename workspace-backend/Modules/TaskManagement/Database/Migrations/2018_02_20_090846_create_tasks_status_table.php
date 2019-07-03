<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\TaskManagement\Entities\TaskStatus;

class CreateTasksStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskStatus::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(TaskStatus::slug, 60)->unique();
            $table->string(TaskStatus::title, 60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskStatus::table);
    }
}
