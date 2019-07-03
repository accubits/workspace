<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\HrmManagement\Entities\HrmKraQuestion;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\HrmManagement\Entities\HrmKraQuestionType;
use Modules\OrgManagement\Entities\Organization;
use Modules\UserManagement\Entities\User;


class CreateHrmKraQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HrmKraQuestion::table, function (Blueprint $table) {
                     
            $table->increments('id');
            $table->string(HrmKraQuestion::slug, 60)->unique();
            $table->integer(HrmKraQuestion::org_id)->unsigned();
            $table->integer(HrmKraQuestion::kra_module_id)->unsigned();
            $table->integer(HrmKraQuestion::kra_question_type_id)->unsigned();
            $table->integer(HrmKraQuestion::order_no)->unsigned();
            $table->mediumText(HrmKraQuestion::question);
            $table->integer(HrmKraQuestion::enable_comment)->default(0);
            $table->integer(HrmKraQuestion::creator_user_id)->unsigned();
            $table->timestamps();

            $table->foreign(HrmKraQuestion::org_id)->references('id')->on(Organization::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestion::kra_module_id)->references('id')->on(HrmKraModule::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestion::kra_question_type_id)->references('id')->on(HrmKraQuestionType::table)->onDelete('cascade');
            $table->foreign(HrmKraQuestion::creator_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HrmKraQuestion::table);
    }
}
