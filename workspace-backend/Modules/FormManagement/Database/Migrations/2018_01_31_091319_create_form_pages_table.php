<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormPage;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormComponents;

class CreateFormPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormPage::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(FormPage::form_page_slug,60)->unique();
            $table->integer(FormPage::form_master_id)->unsigned();
            $table->integer(FormPage::form_components_id)->unsigned();
            $table->string(FormPage::page_title,60)->index();
            $table->timestamps();
            
            $table->foreign(FormPage::form_components_id)->references('id')->on(FormComponents::table)->onDelete('cascade');
            $table->foreign(FormPage::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormPage::table);
    }
}
