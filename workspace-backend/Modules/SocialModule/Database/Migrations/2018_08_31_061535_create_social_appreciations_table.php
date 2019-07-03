<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SocialModule\Entities\SocialAppreciation;
use Modules\UserManagement\Entities\User;
use Modules\OrgManagement\Entities\Organization;
use Modules\SocialModule\Entities\SocialLookup;

class CreateSocialAppreciationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(SocialAppreciation::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(SocialAppreciation::slug, 60)->unique();
            $table->integer(SocialAppreciation::org_id)->unsigned();
            $table->string(SocialAppreciation::title, 150)->index();
            $table->mediumText(SocialAppreciation::description);
            $table->integer(SocialAppreciation::creator_user_id)->unsigned();
            $table->boolean(SocialAppreciation::notify_appreciation_to_all);
            $table->boolean(SocialAppreciation::has_duration)->default(0);
            $table->dateTime(SocialAppreciation::duration_start)->nullable();
            $table->dateTime(SocialAppreciation::duration_end)->nullable();
            $table->integer(SocialAppreciation::status_id)->unsigned();

            $table->timestamps();

            $table->foreign(SocialAppreciation::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
            $table->foreign(SocialAppreciation::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(SocialAppreciation::status_id)->references('id')->on(SocialLookup::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(SocialAppreciation::table);
    }
}
