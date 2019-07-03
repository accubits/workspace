<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormPageComponentsMap;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormPage;
use Modules\FormManagement\Entities\FormComponents;

class CreateFormPageComponentsMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormPageComponentsMap::table, function (Blueprint $table) {
            // composite primary key
            $table->primary([FormPageComponentsMap::form_page_id, FormPageComponentsMap::form_components_id]);
            
            $table->integer(FormPageComponentsMap::form_master_id)->unsigned();
            $table->unsignedInteger(FormPageComponentsMap::form_page_id);
            $table->unsignedInteger(FormPageComponentsMap::form_components_id);
            $table->integer(FormPageComponentsMap::fpc_sort_no)->unsigned();
            $table->timestamps();

            $table->foreign(FormPageComponentsMap::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(FormPageComponentsMap::form_page_id)->references('id')->on(FormPage::table)->onDelete('cascade');
            $table->foreign(FormPageComponentsMap::form_components_id)->references('id')->on(FormComponents::table)->onDelete('cascade');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormPageComponentsMap::table);
    }
}
