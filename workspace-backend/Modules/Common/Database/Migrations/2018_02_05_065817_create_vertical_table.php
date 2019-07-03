<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Common\Entities\Vertical;

class CreateVerticalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Vertical::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Vertical::slug, 60);
            $table->string(Vertical::name, 100);
            $table->string(Vertical::description, 250)->nullable();
            $table->boolean(Vertical::is_active);
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
        Schema::dropIfExists(Vertical::table);
    }
}
