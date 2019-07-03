<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\PartnerManagement\Entities\PartnerManager;
use Modules\PartnerManagement\Entities\Partner;
use \Modules\PartnerManagement\Entities\PartnerManagerPartnerMap;

class CreatePartnerManagerPartnerMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(PartnerManagerPartnerMap::table, function (Blueprint $table) {
            $table->integer(PartnerManagerPartnerMap::partner_manager_id)->unsigned();
            $table->integer(PartnerManagerPartnerMap::partner_id)->unsigned();

            $table->foreign(PartnerManagerPartnerMap::partner_manager_id)->references('id')->on(PartnerManager::table)->onDelete('cascade');
            $table->foreign(PartnerManagerPartnerMap::partner_id)->references('id')->on(Partner::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(PartnerManagerPartnerMap::table);
    }
}
