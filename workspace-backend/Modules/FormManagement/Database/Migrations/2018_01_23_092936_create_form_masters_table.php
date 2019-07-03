<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormMaster;
use Modules\FormManagement\Entities\FormAccessType;
use Modules\FormManagement\Entities\FormStatus;
use Modules\UserManagement\Entities\User;

class CreateFormMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormMaster::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(FormMaster::form_slug,60)->unique();
            $table->string(FormMaster::form_title,100)->index();
            $table->string(FormMaster::description,250)->index();
            $table->integer(FormMaster::form_access_type_id)->unsigned();
            $table->integer(FormMaster::form_status_id)->unsigned();
            $table->boolean(FormMaster::is_template);
            $table->boolean(FormMaster::is_archived);
            $table->boolean(FormMaster::is_published);
            $table->boolean(FormMaster::allow_multi_submit)->default(0)->nullable();
            $table->integer(FormMaster::creator_user_id)->unsigned();
            $table->timestamps();
            
            
            $table->foreign(FormMaster::form_access_type_id)->references('id')->on(FormAccessType::table)->onDelete('cascade');
            $table->foreign(FormMaster::form_status_id)->references('id')->on(FormStatus::table)->onDelete('cascade');
            $table->foreign(FormMaster::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormMaster::table);
    }
}
