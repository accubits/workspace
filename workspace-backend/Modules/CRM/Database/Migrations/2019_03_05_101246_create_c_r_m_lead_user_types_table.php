<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadUserType;

class CreateCRMLeadUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadUserType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(CRMLeadUserType::type_name, 15)->unique();
            $table->string(CRMLeadUserType::type_displayname, 30);

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
        Schema::dropIfExists(CRMLeadUserType::table);
    }
}
