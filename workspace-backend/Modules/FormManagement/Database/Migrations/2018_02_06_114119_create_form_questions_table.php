<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormQuestion;

class CreateFormQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormQuestion::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormQuestion::form_components_id)->unsigned()->unique();
            $table->string(FormQuestion::form_question_text,250)->index();
            $table->boolean(FormQuestion::is_mandatory);
            $table->boolean(FormQuestion::has_unique_answer)->nullable();
            $table->boolean(FormQuestion::randomize_answeroption)->nullable();
            $table->boolean(FormQuestion::allow_otheroption)->nullable();
            $table->integer(FormQuestion::min_range)->nullable();
            $table->integer(FormQuestion::max_range)->nullable();
            $table->string(FormQuestion::currency_type)->nullable();
                     
            $table->timestamps();
            
            $table->foreign(FormQuestion::form_components_id)->references('id')->on(FormComponents::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormQuestion::table);
    }
}
