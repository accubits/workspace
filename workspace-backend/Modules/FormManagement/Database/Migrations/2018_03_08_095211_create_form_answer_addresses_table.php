<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswers;
use Modules\FormManagement\Entities\FormAnswerAddress;
use Modules\Common\Entities\Country;

class CreateFormAnswerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerAddress::table, function (Blueprint $table) {
            $table->increments('id');

            $table->integer(FormAnswerAddress::form_answers_id)->unsigned();          
            $table->string(FormAnswerAddress::street_address,150)->index();
            $table->string(FormAnswerAddress::address_line2,150)->index();
            $table->string(FormAnswerAddress::city,80)->index();
            $table->string(FormAnswerAddress::state,80)->index();
            $table->integer(FormAnswerAddress::country_id)->unsigned()->nullable();
            $table->string(FormAnswerAddress::zip_code, 10)->index();
            
            $table->timestamps();

            $table->foreign(FormAnswerAddress::form_answers_id)->references('id')->on(FormAnswers::table)->onDelete('cascade');
            $table->foreign(FormAnswerAddress::country_id)->references('id')->on(Country::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerAddress::table);
    }
}
