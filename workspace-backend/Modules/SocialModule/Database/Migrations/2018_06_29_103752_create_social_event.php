<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialLookup;
use Modules\SocialModule\Entities\SocialEvent;
use Modules\UserManagement\Entities\User;


class CreateSocialEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialEvent::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialEvent::event_slug, 60)->unique();
            $table->string(SocialEvent::event_title, 60);
            $table->mediumText(SocialEvent::description);
            $table->dateTime(SocialEvent:: event_start_date);
            $table->dateTime(SocialEvent:: event_end_date);
            $table->integer(SocialEvent::org_id)->unsigned();
            $table->integer(SocialEvent::creator_user_id)->unsigned();
            $table->boolean(SocialEvent:: is_allday)->nullable();

            $table->integer(SocialEvent::reminder_count)->unsigned()->nullable();
            $table->integer(SocialEvent::reminder_type_id)->unsigned()->nullable();
            $table->dateTime(SocialEvent::reminder_datetime)->nullable();
            $table->boolean(SocialEvent::is_reminder_sent)->default(0);

            $table->string(SocialEvent::location,60);
            $table->boolean(SocialEvent:: is_event_to_all);

            $table->integer(SocialEvent::availabilty_lookup_id)->unsigned()->nullable();
            $table->integer(SocialEvent::repeat_lookup_id)->unsigned()->nullable();
            $table->integer(SocialEvent::importance_lookup_id)->unsigned()->nullable();
            $table->integer(SocialEvent::user_calender_id )->unsigned()->nullable();

            $table->timestamps();
            $table->foreign(SocialEvent::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialEvent::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialEvent::availabilty_lookup_id)->references('id')->on(SocialLookup::table)->onDelete('cascade');
            $table->foreign(SocialEvent::repeat_lookup_id)->references('id')->on(SocialLookup::table)->onDelete('cascade');
            $table->foreign(SocialEvent::importance_lookup_id)->references('id')->on(SocialLookup::table)->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialEvent::table);
    }
}
