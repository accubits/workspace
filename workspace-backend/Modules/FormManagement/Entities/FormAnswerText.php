<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerText extends Model
{

    const table = "form_answer_text";
    
    const form_answers_id = "form_answers_id";
    const answer_text = "answer_text";
    const answer_text2 = "answer_text2";    
    
    protected $table = FormAnswerText::table;    
    protected $fillable = [
        FormAnswerText::form_answers_id,
        FormAnswerText::answer_text,
        FormAnswerText::answer_text2
        ];
}
