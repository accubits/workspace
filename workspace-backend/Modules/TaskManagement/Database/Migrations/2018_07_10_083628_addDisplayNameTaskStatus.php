<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskStatus;

class AddDisplayNameTaskStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(TaskStatus::table, function (Blueprint $table) {
            $table->string(TaskStatus::display_name, 60)
                ->nullable()
                ->after(TaskStatus::title);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(TaskStatus::table, function (Blueprint $table) {
            $table->dropColumn(TaskStatus::display_name);
        });

    }
}
