<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormSection;
use Modules\FormManagement\Entities\FormComponents;
use Modules\FormManagement\Entities\FormPage;

class CreateFormSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormSection::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormSection::form_components_id)->unsigned();
            $table->integer(FormSection::form_page_id)->unsigned();            
            $table->string(FormSection::fs_title,60)->index();
            $table->string(FormSection::fs_desc,250)->index()->nullable();
            $table->timestamps();
            
            $table->foreign(FormSection::form_page_id)->references('id')->on(FormPage::table)->onDelete('cascade');
            $table->foreign(FormSection::form_components_id)->references('id')->on(FormComponents::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormSection::table);
    }
}
