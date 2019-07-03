<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormQnsLikertColumns;
use Modules\FormManagement\Entities\FormQuestion;

class CreateFormQnsLikertColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormQnsLikertColumns::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormQnsLikertColumns::form_question_id)->unsigned();
            $table->string(FormQnsLikertColumns::likert_column,250)->index();
            $table->timestamps();
            
            $table->foreign(FormQnsLikertColumns::form_question_id)->references('id')->on(FormQuestion::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormQnsLikertColumns::table);
    }
}
