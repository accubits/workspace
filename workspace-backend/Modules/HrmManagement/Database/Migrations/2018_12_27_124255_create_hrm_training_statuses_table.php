<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmTrainingStatus;

class CreateHrmTrainingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmTrainingStatus::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmTrainingStatus::name, 60);
            $table->string(HrmTrainingStatus::value, 60 );
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
        Schema::dropIfExists(HrmTrainingStatus::table);
    }
}
