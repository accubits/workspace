<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgDesignation;
use Modules\OrgManagement\Entities\Organization;

class CreateOrgDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgDesignation::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgDesignation::slug, 60);
            $table->integer(OrgDesignation::org_id)->unsigned();
            $table->string(OrgDesignation::name, 100);
            $table->string(OrgDesignation::description, 250);

            $table->timestamps();
            
            $table->foreign(OrgDesignation::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgDesignation::table);
    }
}
