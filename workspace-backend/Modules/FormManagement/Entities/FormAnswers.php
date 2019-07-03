<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswers extends Model
{
    const table = "form_answers";
    
    const form_answersheet_id = "form_answersheet_id";
    const form_components_id = "form_components_id";
    const form_question_id = "form_question_id";
    const form_qns_options_id = "form_qns_options_id";
    
    protected $table = FormAnswers::table;    
    protected $fillable = [
        FormAnswers::form_answersheet_id,
        FormAnswers::form_components_id,
        FormAnswers::form_question_id,
        FormAnswers::form_qns_options_id
        ];
}
