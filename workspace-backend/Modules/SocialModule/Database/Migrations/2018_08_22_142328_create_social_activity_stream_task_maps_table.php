<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialActivityStreamTaskMap;
use Modules\SocialModule\Entities\SocialActivityStreamMaster;
use Modules\TaskManagement\Entities\Task;

class CreateSocialActivityStreamTaskMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialActivityStreamTaskMap::table, function (Blueprint $table) {
            $table->primary([SocialActivityStreamTaskMap::activity_stream_master_id,SocialActivityStreamTaskMap::task_id]);
            $table->unsignedInteger(SocialActivityStreamTaskMap::activity_stream_master_id);
            $table->unsignedInteger(SocialActivityStreamTaskMap::task_id);
            $table->timestamps();
            
            $table->foreign(SocialActivityStreamTaskMap::activity_stream_master_id,'asm_task_foreign')
                    ->references('id')->on(SocialActivityStreamMaster::table)->onDelete('cascade');
            $table->foreign(SocialActivityStreamTaskMap::task_id)
                    ->references('id')->on(Task::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialActivityStreamTaskMap::table);
    }
}
