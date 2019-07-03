<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmHolidays;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;

class CreateHrmHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmHolidays::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmHolidays::slug, 60)->unique();
            $table->integer(HrmHolidays::org_id)->unsigned();
            $table->integer(HrmHolidays::creator_id)->unsigned();
            $table->string(HrmHolidays::name, 150);
            $table->string(HrmHolidays::description, 250);
            $table->date(HrmHolidays::holiday_date);
            $table->boolean(HrmHolidays::is_restricted)->default(0);
            $table->boolean(HrmHolidays::is_repeat_yearly)->default(0);
            $table->timestamps();

            $table->foreign(HrmHolidays::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmHolidays::creator_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmHolidays::table);
    }
}
