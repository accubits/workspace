<?php

namespace Modules\FormManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class FormAnswerLongText extends Model
{
    
    const table = "form_answer_long_text";
    
    const form_answers_id = "form_answers_id";
    const answer_longtext = "answer_longtext";
        
    protected $table = FormAnswerLongText::table;
    
    protected $fillable = [
        FormAnswerLongText::form_answers_id,
        FormAnswerLongText::answer_longtext
        ];
}
