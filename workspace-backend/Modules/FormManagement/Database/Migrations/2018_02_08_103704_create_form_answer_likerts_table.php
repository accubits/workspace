<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswerLikert;
use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Entities\FormQnsLikertColumns;
use Modules\FormManagement\Entities\FormQnsLikertStatement;

class CreateFormAnswerLikertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerLikert::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerLikert::form_answers_id)->unsigned();
            $table->integer(FormAnswerLikert::form_qns_likert_stmt_id)->unsigned();
            $table->integer(FormAnswerLikert::form_qns_likert_col_id)->unsigned();
            $table->timestamps();

            $table->foreign(FormAnswerLikert::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
            $table->foreign(FormAnswerLikert::form_qns_likert_stmt_id)->references('id')->on(FormQnsLikertStatement::table)->onDelete('cascade');
            $table->foreign(FormAnswerLikert::form_qns_likert_col_id)->references('id')->on(FormQnsLikertColumns::table)->onDelete('cascade');            
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerLikert::table);
    }
}
