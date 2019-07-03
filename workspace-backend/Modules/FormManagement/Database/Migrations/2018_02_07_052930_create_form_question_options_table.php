<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormQuestionOptions;
use Modules\FormManagement\Entities\FormQuestion;

class CreateFormQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormQuestionOptions::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormQuestionOptions::form_question_id)->unsigned();
            $table->integer(FormQuestionOptions::fqo_sort_no)->unsigned();
            $table->string(FormQuestionOptions::option_text,250)->index();
            $table->integer(FormQuestionOptions::max_quantity)->default(0)->unsigned();
            $table->timestamps();

            $table->foreign(FormQuestionOptions::form_question_id)->references('id')->on(FormQuestion::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormQuestionOptions::table);
    }
}
