<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswerDatetime;
use Modules\FormManagement\Entities\FormAnswers;

class CreateFormAnswerDatetimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerDatetime::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerDatetime::form_answers_id)->unsigned();
            $table->dateTime(FormAnswerDatetime::answer_datetime)->nullable();
            $table->timestamps();

            $table->foreign(FormAnswerDatetime::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerDatetime::table);
    }
}
