<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TaskManagement\Entities\TaskCommentsLike;
use Modules\TaskManagement\Entities\TaskComments;
use Modules\UserManagement\Entities\User;

class CreateTaskCommentsLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(TaskCommentsLike::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(TaskCommentsLike::comment_id)->unsigned();
            $table->boolean(TaskCommentsLike::like)->default(0);
            $table->integer(TaskCommentsLike::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(TaskCommentsLike::comment_id)->references('id')->on(TaskComments::table)->onDelete('cascade');
            $table->foreign(TaskCommentsLike::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(TaskCommentsLike::table);
    }
}
