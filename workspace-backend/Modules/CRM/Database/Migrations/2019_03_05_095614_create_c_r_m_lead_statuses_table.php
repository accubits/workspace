<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\CRM\Entities\CRMLeadStatus;

class CreateCRMLeadStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CRMLeadStatus::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(CRMLeadStatus::status_name, 15)->unique();
            $table->string(CRMLeadStatus::status_displayname, 30);
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
        Schema::dropIfExists(CRMLeadStatus::table);
    }
}
