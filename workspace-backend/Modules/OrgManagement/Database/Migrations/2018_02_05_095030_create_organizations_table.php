<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Modules\OrgManagement\Entities\Organization;
use Modules\Common\Entities\Vertical;
use Modules\Common\Entities\Country;
use Modules\PartnerManagement\Entities\Partner;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Organization::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(Organization::name, 100)->index();
            $table->string(Organization::slug, 60);
            $table->string(Organization::description, 250)->nullable();
            $table->integer(Organization::vertical_id)->unsigned();
            $table->integer(Organization::country_id)->unsigned();
            $table->integer(Organization::partner_id)->unsigned();
            $table->string(Organization::bg_image,200)->nullable();
            $table->string(Organization::bg_image_path,200)->nullable();
            $table->boolean(Organization::is_bg_default_img)->default(0);
            $table->string(Organization::dashboard_message, 250)->nullable();
            $table->string(Organization::timezone, 50)->nullable();
            $table->integer(Organization::storage)->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign(Organization::vertical_id)->references('id')->on(Vertical::table)->onDelete('cascade');
            $table->foreign(Organization::country_id)->references('id')->on(Country::table)->onDelete('cascade');
            $table->foreign(Organization::partner_id)->references('id')->on(Partner::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists(Organization::table);
        Schema::enableForeignKeyConstraints();
    }
}
