<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Entities\FormAnswerInteger;

class CreateFormAnswerIntegersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerInteger::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerInteger::form_answers_id)->unsigned();
            $table->integer(FormAnswerInteger::answer_integer)->nullable();
            $table->timestamps();

            $table->foreign(FormAnswerInteger::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerInteger::table);
    }
}
