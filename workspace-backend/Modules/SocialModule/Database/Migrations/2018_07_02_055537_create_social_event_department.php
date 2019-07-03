<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialEventDepartment;
use Modules\OrgManagement\Entities\OrgDepartment;
use Modules\SocialModule\Entities\SocialEvent;



class CreateSocialEventDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEventDepartment::table, function (Blueprint $table) {
            $table->primary([SocialEventDepartment::department_id , SocialEventDepartment::social_event_id],'migrations');
            
            $table->unsignedInteger(SocialEventDepartment::department_id);
            $table->unsignedInteger(SocialEventDepartment::social_event_id);
            
            $table->timestamps();

            $table->foreign(SocialEventDepartment::department_id)->references('id')->on(OrgDepartment::table)->onDelete('cascade');
            $table->foreign(SocialEventDepartment::social_event_id)->references('id')->on(SocialEvent::table)->onDelete('cascade');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_event_department');
    }
}
