<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\OrgManagement\Entities\OrgLicenseRequestsMap;
use \Modules\OrgManagement\Entities\OrgLicense;
use \Modules\OrgManagement\Entities\OrgLicenseRequest;

class CreateOrgLicenseRequestsMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgLicenseRequestsMap::table, function (Blueprint $table) {

            $table->primary([OrgLicenseRequestsMap::license_request_id ,OrgLicenseRequestsMap::org_license_id],'for migrations');
            $table->unsignedInteger(OrgLicenseRequestsMap::org_license_id);
            $table->unsignedInteger(OrgLicenseRequestsMap::license_request_id);
            $table->timestamps();

            $table->foreign(OrgLicenseRequestsMap::org_license_id)->references('id')->on(OrgLicense::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRequestsMap::license_request_id)->references('id')->on(OrgLicenseRequest::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgLicenseRequestsMap::table);
    }
}
