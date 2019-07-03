<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\FormManagement\Entities\FormAnswerSheet;
use Modules\UserManagement\Entities\User;
use Modules\FormManagement\Entities\FormMaster;

class CreateFormAnswerSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(FormAnswerSheet::table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer(FormAnswerSheet::form_master_id)->unsigned();
            $table->string(FormAnswerSheet::slug,60)->unique();
            $table->dateTime(FormAnswerSheet::submit_datetime);
            $table->integer(FormAnswerSheet::form_user_id)->nullable()->unsigned();
            $table->boolean(FormAnswerSheet::is_discarded)->default(0)->nullable();
            $table->timestamps();
            
            
            $table->foreign(FormAnswerSheet::form_master_id)->references('id')->on(FormMaster::table)->onDelete('cascade');
            $table->foreign(FormAnswerSheet::form_user_id)->references('id')->on(User::table)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(FormAnswerSheet::table);
    }
}
