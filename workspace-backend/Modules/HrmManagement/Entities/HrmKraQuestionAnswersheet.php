<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmKraQuestionAnswersheet extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const kra_module_id = 'kra_module_id';
    const submit_datetime = 'submit_datetime';
    const user_id = 'user_id';

    const table       = 'hrm_kra_question_answersheets';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmKraQuestionAnswersheet::table;

    protected $fillable = [HrmKraQuestionAnswersheet::slug, HrmKraQuestionAnswersheet::kra_module_id];
}
