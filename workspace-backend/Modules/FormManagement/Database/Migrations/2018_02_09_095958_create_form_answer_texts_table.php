<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswerText;
use Modules\FormManagement\Entities\FormAnswers;

class CreateFormAnswerTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerText::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerText::form_answers_id)->unsigned();
            $table->string(FormAnswerText::answer_text,150)->index();
            $table->string(FormAnswerText::answer_text2,50)->nullable()->index();
            $table->timestamps();

            $table->foreign(FormAnswerText::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerText::table);
    }
}
