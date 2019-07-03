<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\HrmManagement\Entities\HrmKraQuestionAnswersheet;


class CreateHrmKraQuestionAnswersheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmKraQuestionAnswersheet::table, function (Blueprint $table) {
            $table->increments('id');
            $table->string(HrmKraQuestionAnswersheet::slug, 60)->unique();
            $table->integer(HrmKraQuestionAnswersheet::org_id)->unsigned();
            $table->integer(HrmKraQuestionAnswersheet::kra_module_id)->unsigned();
            $table->dateTime(HrmKraQuestionAnswersheet::submit_datetime)->nullable();
            $table->integer(HrmKraQuestionAnswersheet::user_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmKraQuestionAnswersheet::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestionAnswersheet::kra_module_id)->references('id')->on(HrmKraModule::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestionAnswersheet::user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmKraQuestionAnswersheet::table);
    }
}
