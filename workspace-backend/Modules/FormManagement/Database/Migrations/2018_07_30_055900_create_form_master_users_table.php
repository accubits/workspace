<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormMasterUsers;
use Modules\FormManagement\Entities\FormMaster;
use Modules\UserManagement\Entities\User;

class CreateFormMasterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormMasterUsers::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormMasterUsers::form_master_id)->unsigned();
            $table->integer(FormMasterUsers::user_id)->unsigned();
            $table->string(FormMasterUsers::form_permission,10)->index();
            $table->integer(FormMasterUsers::creator_id)->unsigned();
            $table->timestamps();
            
            $table->foreign(FormMasterUsers::form_master_id)->references('id')
                    ->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(FormMasterUsers::user_id)->references('id')
                    ->on(User::table)->onDelete('cascade');
            $table->foreign(FormMasterUsers::creator_id)->references('id')
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
        Schema::dropIfExists(FormMasterUsers::table);
    }
}
