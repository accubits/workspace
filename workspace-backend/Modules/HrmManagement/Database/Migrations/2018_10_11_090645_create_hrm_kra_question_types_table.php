<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmKraQuestionType;

class CreateHrmKraQuestionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmKraQuestionType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmKraQuestionType::type_name, 10);
            $table->string(HrmKraQuestionType::display_name, 15);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmKraQuestionType::table);
    }
}
