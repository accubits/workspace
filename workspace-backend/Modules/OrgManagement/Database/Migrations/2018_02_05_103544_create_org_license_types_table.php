<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgLicenseType;

class CreateOrgLicenseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgLicenseType::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgLicenseType::slug, 100)->unique();
            $table->string(OrgLicenseType::name, 100)->unique();
            $table->integer(OrgLicenseType::duration);
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
        Schema::dropIfExists(OrgLicenseType::table);
    }
}
