<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\OrgManagement\Entities\OrgLicense;

class CreateOrganizationLicenseMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_license_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->integer('license_id')->unsigned();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();

            $table->foreign('org_id')->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign('license_id')->references('id')->on(OrgLicense::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_license_mapping');
    }
}
