<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\PartnerManagement\Entities\Partner;
use Modules\OrgManagement\Entities\Organization;

class CreateOrgLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgLicense::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgLicense::name, 100)->index();
            $table->string(OrgLicense::slug, 60)->unique();
            $table->string(OrgLicense::key, 100)->unique();
            $table->integer(OrgLicense::max_users)->unsigned();
            $table->integer(OrgLicense::license_type_id)->unsigned();
            $table->integer(OrgLicense::partner_id)->unsigned();
            $table->integer(OrgLicense::org_id)->unsigned()->nullable();
            $table->boolean(OrgLicense::license_status)->default(0);
            $table->boolean(OrgLicense::upcoming_license)->default(0);
            $table->timestamps();

            $table->foreign(OrgLicense::license_type_id)->references('id')->on(OrgLicenseType::table)->onDelete('cascade');
            $table->foreign(OrgLicense::partner_id)->references('id')->on(Partner::table)->onDelete('cascade');
            $table->foreign(OrgLicense::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgLicense::table);
    }
}
