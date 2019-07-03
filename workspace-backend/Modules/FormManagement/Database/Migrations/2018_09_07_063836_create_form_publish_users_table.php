<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormPublishUsers;
use Modules\FormManagement\Entities\FormMaster;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;

class CreateFormPublishUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormPublishUsers::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormPublishUsers::org_id)->unsigned()->nullable();
            $table->integer(FormPublishUsers::form_master_id)->unsigned();
            $table->integer(FormPublishUsers::user_id)->unsigned();
            $table->integer(FormPublishUsers::creator_id)->unsigned();
            $table->boolean(FormPublishUsers::has_submitted)->default(0);

            $table->timestamps();

            $table->foreign(FormPublishUsers::org_id)->references('id')
                    ->on(Organization::table)->onDelete('cascade');
            $table->foreign(FormPublishUsers::form_master_id)->references('id')
                    ->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(FormPublishUsers::user_id)->references('id')
                    ->on(User::table)->onDelete('cascade');
            $table->foreign(FormPublishUsers::creator_id)->references('id')
                    ->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormPublishUsers::table);
    }
}

 
