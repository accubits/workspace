<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormQnsLikertStatement;
use Modules\FormManagement\Entities\FormQuestion;

class CreateFormQnsLikertStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormQnsLikertStatement::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormQnsLikertStatement::form_question_id)->unsigned();
            $table->string(FormQnsLikertStatement::likert_statement,250)->index();
            $table->timestamps();
            
            $table->foreign(FormQnsLikertStatement::form_question_id)->references('id')->on(FormQuestion::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormQnsLikertStatement::table);
    }
}
