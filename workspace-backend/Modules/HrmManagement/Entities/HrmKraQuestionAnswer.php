<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmKraQuestionAnswer extends Model
{
    const kra_module_answersheet_id = 'kra_module_answersheet_id';
    const kra_question_id = 'kra_question_id';
    const user_id = 'user_id';    
    const answer_integer = 'answer_integer';
    const answer_text = 'answer_text';
    const comment = 'comment';

    const table       = 'hrm_kra_question_answers';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmKraQuestionAnswer::table;

    protected $fillable = [
        HrmKraQuestionAnswer::kra_module_answersheet_id,
        HrmKraQuestionAnswer::kra_question_id,
        HrmKraQuestionAnswer::answer_integer,
        HrmKraQuestionAnswer::answer_text,
        HrmKraQuestionAnswer::comment
            ];
}
