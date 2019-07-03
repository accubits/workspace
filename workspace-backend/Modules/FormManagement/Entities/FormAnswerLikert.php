<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerLikert extends Model
{
 
    const table = "form_answer_likert";
    
    const form_answers_id = "form_answers_id";
    const form_qns_likert_stmt_id = "form_qns_likert_stmt_id";
    const form_qns_likert_col_id = "form_qns_likert_col_id";
    
    
    protected $table = FormAnswerLikert::table;
    protected $fillable = [
        FormAnswerLikert::form_answers_id,
        FormAnswerLikert::form_qns_likert_stmt_id,
        FormAnswerLikert::form_qns_likert_col_id
        ];
}
