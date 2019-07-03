<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\OrgAdmin;

class CreateOrgAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(OrgAdmin::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(OrgAdmin::org_id)->unsigned();
            $table->integer(OrgAdmin::user_id)->unsigned();
            $table->boolean(OrgAdmin::is_active)->default(1);
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
        Schema::dropIfExists(OrgAdmin::table);
    }
}
