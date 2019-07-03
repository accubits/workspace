<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\HrmManagement\Entities\HrmLeaveTypeCategory;

class CreateHrmLeaveTypeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmLeaveTypeCategory::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmLeaveTypeCategory::slug, 60)->unique();
            $table->string(HrmLeaveTypeCategory::category_display_name, 60)->unique();
            $table->string(HrmLeaveTypeCategory::category_name, 60)->unique();
            $table->boolean(HrmLeaveTypeCategory::is_active)->default(0);
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
        Schema::dropIfExists(HrmLeaveTypeCategory::table);
    }
}
