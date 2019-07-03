<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgLicenseRenewRequestMap;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseRequest;
use Modules\UserManagement\Entities\User;

class CreateOrgLicenseRenewRequestMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgLicenseRenewRequestMap::table, function (Blueprint $table) {
            $table->integer(OrgLicenseRenewRequestMap::org_license_id)->unsigned();
            $table->integer(OrgLicenseRenewRequestMap::license_request_id)->unsigned();
            $table->integer(OrgLicenseRenewRequestMap::creator_id)->unsigned();
            $table->timestamps();

            $table->foreign(OrgLicenseRenewRequestMap::org_license_id)->references('id')->on(OrgLicense::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRenewRequestMap::license_request_id)->references('id')->on(OrgLicenseRequest::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRenewRequestMap::creator_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgLicenseRenewRequestMap::table);
    }
}
