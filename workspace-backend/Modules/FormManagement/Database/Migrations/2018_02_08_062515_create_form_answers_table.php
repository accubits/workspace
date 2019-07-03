<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Entities\FormAnswerSheet;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormQuestion;
use Modules\FormManagement\Entities\FormQuestionOptions;

class CreateFormAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswers::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswers::form_answersheet_id)->unsigned();
            $table->integer(FormAnswers::form_components_id)->unsigned();
            $table->integer(FormAnswers::form_question_id)->unsigned();
            $table->integer(FormAnswers::form_qns_options_id)->nullable()->unsigned();
            $table->timestamps();

            $table->foreign(FormAnswers::form_answersheet_id)->references('id')->on(FormAnswerSheet::table)->onDelete('cascade');
            $table->foreign(FormAnswers::form_components_id)->references('id')->on(FormComponents::table)->onDelete('cascade');
            $table->foreign(FormAnswers::form_question_id)->references('id')->on(FormQuestion::table)->onDelete('cascade');
            $table->foreign(FormAnswers::form_qns_options_id)->references('id')->on(FormQuestionOptions::table)->onDelete('cascade');             
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswers::table);
    }
}
