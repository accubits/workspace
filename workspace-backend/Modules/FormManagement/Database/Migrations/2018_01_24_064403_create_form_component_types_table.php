<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormComponentType;

class CreateFormComponentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormComponentType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(FormComponentType::cmp_type_displayname, 40)->unique();
            $table->string(FormComponentType::cmp_type_name, 30)->unique();
            $table->boolean(FormComponentType::is_active);
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
        Schema::dropIfExists(FormComponentType::table);
    }
}
