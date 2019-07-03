<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswerLongText;
use Modules\FormManagement\Entities\FormAnswers;

class CreateFormAnswerLongTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerLongText::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerLongText::form_answers_id)->unsigned();
            $table->longText(FormAnswerLongText::answer_longtext);
            $table->timestamps();
            
            $table->foreign(FormAnswerLongText::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerLongText::table);
    }
}
