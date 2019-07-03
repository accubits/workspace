<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormSection;
use Modules\FormManagement\Entities\FormPage;
use Modules\FormManagement\Entities\FormComponentType;

class CreateFormComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormComponents::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormComponents::form_master_id)->unsigned();

            $table->integer(FormComponents::fc_sort_no)->unsigned();
            $table->integer(FormComponents::form_component_type_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(FormComponents::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(FormComponents::form_component_type_id)->references('id')->on(FormComponentType::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormComponents::table);
    }
}
