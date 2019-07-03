<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgLicenseRequest;
use Modules\OrgManagement\Entities\OrgLicenseType;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class CreateOrgLicenseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgLicenseRequest::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(OrgLicenseRequest::request_slug,60)->unique();
            $table->integer(OrgLicenseRequest::org_id)->unsigned()->nullable();
            $table->integer(OrgLicenseRequest::license_type_id)->unsigned(); 
            $table->integer(OrgLicenseRequest::max_users)->unsigned();
            $table->integer(OrgLicenseRequest::requesting_user_id)->unsigned();
            $table->integer(OrgLicenseRequest::parent_request_id)->nullable()->unsigned();
            $table->string(OrgLicenseRequest::to_user_group, 20);
            $table->boolean(OrgLicenseRequest::is_forward)->default(0);
            $table->boolean(OrgLicenseRequest::is_approved)->default(0);
            $table->boolean(OrgLicenseRequest::is_cancelled)->default(0);
            $table->boolean(OrgLicenseRequest::is_renewal)->default(0);
            $table->timestamps();

            $table->foreign(OrgLicenseRequest::license_type_id)->references('id')->on(OrgLicenseType::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRequest::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRequest::requesting_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(OrgLicenseRequest::parent_request_id)->references('id')->on(OrgLicenseRequest::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(OrgLicenseRequest::table);
    }
}
