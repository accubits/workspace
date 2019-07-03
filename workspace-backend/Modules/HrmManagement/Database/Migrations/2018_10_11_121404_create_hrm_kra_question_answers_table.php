<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmKraQuestionAnswer;
use Modules\HrmManagement\Entities\HrmKraQuestionAnswersheet;
use Modules\HrmManagement\Entities\HrmKraQuestion;
use Modules\UserManagement\Entities\User;

class CreateHrmKraQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmKraQuestionAnswer::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(HrmKraQuestionAnswer::kra_module_answersheet_id)->unsigned();
            $table->integer(HrmKraQuestionAnswer::kra_question_id)->unsigned();
            $table->integer(HrmKraQuestionAnswer::user_id)->unsigned();
            $table->integer(HrmKraQuestionAnswer::answer_integer);
            $table->string(HrmKraQuestionAnswer::answer_text)->nullable();
            $table->string(HrmKraQuestionAnswer::comment)->nullable();
            $table->timestamps();

            $table->foreign(HrmKraQuestionAnswer::kra_module_answersheet_id)->references('id')->on(HrmKraQuestionAnswersheet::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestionAnswer::kra_question_id)->references('id')->on(HrmKraQuestion::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestionAnswer::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmKraQuestionAnswer::table);
    }
}
