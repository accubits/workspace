<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmAppraisalCycleApplicable;

class CreateHrmAppraisalCycleApplicablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmAppraisalCycleApplicable::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmAppraisalCycleApplicable::applicable_type, 20)->nullable();
            $table->string(HrmAppraisalCycleApplicable::applicable_type_display_name, 30)->nullable();
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
        Schema::dropIfExists(HrmAppraisalCycleApplicable::table);
    }
}
